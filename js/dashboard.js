// Dashboard interactions and logic
document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const navItems = document.querySelectorAll('.sidebar-nav li a');
    const sections = document.querySelectorAll('.page-section');
    const headerTitle = document.getElementById('page-title');
    const mainActionBtn = document.getElementById('mainActionBtn');
    const btnText = document.getElementById('btnText');
    
    // Modals
    const modals = {
        'events': document.getElementById('createEventModal'),
        'resources': document.getElementById('addResourceModal'),
        'team': document.getElementById('addTeamMemberModal')
    };

    const closeBtns = document.querySelectorAll('.close-modal, .close-modal-btn');
    
    // Config
    const sectionConfig = {
        'dashboard': { title: 'Dashboard Overview', btnText: 'Create Event', targetModal: 'events' },
        'events': { title: 'Event Management', btnText: 'Create Event', targetModal: 'events' },
        'resources': { title: 'Resource Library', btnText: 'Add Resource', targetModal: 'resources' },
        'team': { title: 'Team Directory', btnText: 'Add Member', targetModal: 'team' }
    };
    
    let currentModalTarget = 'events'; // default

    // Navigation logic
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Handle active states on navigation
            navItems.forEach(nav => nav.parentElement.classList.remove('active'));
            this.parentElement.classList.add('active');
            
            // Show corresponding section
            const targetSection = this.getAttribute('data-target');
            
            sections.forEach(section => {
                section.classList.remove('active');
            });
            
            const activeSection = document.getElementById(`section-${targetSection}`);
            if (activeSection) {
                activeSection.classList.add('active');
            }
            
            // Update page header and button
            const config = sectionConfig[targetSection];
            if (config) {
                headerTitle.textContent = config.title;
                if(btnText) btnText.textContent = config.btnText;
                currentModalTarget = config.targetModal;
            }
        });
    });

    // Main action button logic
    if (mainActionBtn) {
        mainActionBtn.addEventListener('click', () => {
             const activeModal = modals[currentModalTarget];
             if(activeModal) {
                 activeModal.classList.add('active');
             }
        });
    }

    // Close logic for all modals
    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if(modal) modal.classList.remove('active');
        });
    });

    // Close modal on outside click
    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.classList.remove('active');
        }
    });

    // Example form submissions
    const forms = [
        { id: 'createEventForm', msg: 'Event created successfully!' },
        { id: 'addResourceForm', msg: 'Resource added successfully!' },
        { id: 'addTeamMemberForm', msg: 'Team member added successfully!' }
    ];

    forms.forEach(f => {
        const formEl = document.getElementById(f.id);
        if (formEl) {
            formEl.addEventListener('submit', (e) => {
                e.preventDefault();
                alert(f.msg);
                const modal = formEl.closest('.modal');
                if(modal) modal.classList.remove('active');
                formEl.reset();
            });
        }
    });

});
