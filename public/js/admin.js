/**
 * Admin Dashboard JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Add animation classes to dashboard cards
    const animateCards = () => {
        const cards = document.querySelectorAll('.card-admin');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('fade-in');
            }, index * 100);
        });
    };

    // Animate stat numbers
    const animateStats = () => {
        const statElements = document.querySelectorAll('.card-admin h3.fw-bold');
        statElements.forEach(stat => {
            const value = parseInt(stat.textContent.replace(/[^\d]/g, ''));
            if (!isNaN(value)) {
                let startValue = 0;
                const duration = 1500;
                const startTime = Date.now();
                
                const updateValue = () => {
                    const currentTime = Date.now();
                    const elapsed = currentTime - startTime;
                    
                    if (elapsed < duration) {
                        const progress = elapsed / duration;
                        const currentValue = Math.floor(value * progress);
                        
                        if (stat.textContent.includes('Rp')) {
                            stat.textContent = 'Rp ' + currentValue.toLocaleString('id-ID');
                        } else {
                            stat.textContent = currentValue;
                        }
                        
                        requestAnimationFrame(updateValue);
                    } else {
                        if (stat.textContent.includes('Rp')) {
                            stat.textContent = 'Rp ' + value.toLocaleString('id-ID');
                        } else {
                            stat.textContent = value;
                        }
                    }
                };
                
                requestAnimationFrame(updateValue);
            }
        });
    };

    // Sidebar active link highlighting
    const highlightActiveLink = () => {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href)) {
                link.classList.add('active');
            }
        });
    };

    // Responsive sidebar toggle
    const setupSidebarToggle = () => {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.querySelector('.admin-sidebar');
        const adminContent = document.querySelector('.admin-content');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                adminSidebar.classList.toggle('show');
                adminContent.classList.toggle('sidebar-shown');
            });
        }
    };

    // Dropdown hover effect
    const setupDropdownHover = () => {
        const dropdowns = document.querySelectorAll('.dropdown');
        
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('mouseenter', function() {
                if (window.innerWidth >= 992) {
                    const dropdownMenu = this.querySelector('.dropdown-menu');
                    if (dropdownMenu) {
                        dropdownMenu.classList.add('show');
                    }
                }
            });
            
            dropdown.addEventListener('mouseleave', function() {
                if (window.innerWidth >= 992) {
                    const dropdownMenu = this.querySelector('.dropdown-menu');
                    if (dropdownMenu) {
                        dropdownMenu.classList.remove('show');
                    }
                }
            });
        });
    };

    // Initialize all functions
    animateCards();
    setTimeout(animateStats, 300);
    highlightActiveLink();
    setupSidebarToggle();
    setupDropdownHover();
}); 