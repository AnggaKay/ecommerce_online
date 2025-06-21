<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Display the specified contact.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        // Tandai sebagai sudah dibaca jika belum
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }
        
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mark a contact message as read.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        
        return back()->with('success', 'Pesan ditandai sebagai sudah dibaca.');
    }

    /**
     * Mark a contact message as unread.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function markAsUnread(Contact $contact)
    {
        $contact->update(['is_read' => false]);
        
        return back()->with('success', 'Pesan ditandai sebagai belum dibaca.');
    }

    /**
     * Remove the specified contact from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
    
    /**
     * Mark multiple contacts as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkMarkAsRead(Request $request)
    {
        $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id'
        ]);
        
        Contact::whereIn('id', $request->contact_ids)->update(['is_read' => true]);
        
        return back()->with('success', 'Pesan terpilih ditandai sebagai sudah dibaca.');
    }
    
    /**
     * Delete multiple contacts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id'
        ]);
        
        Contact::whereIn('id', $request->contact_ids)->delete();
        
        return back()->with('success', 'Pesan terpilih berhasil dihapus.');
    }
} 