// JavaScript for employee management functionality

// Handle edit button clicks
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const nip = this.getAttribute('data-nip');
        const nama = this.getAttribute('data-nama');
        const jenis_kelamin = this.getAttribute('data-jenis_kelamin');
        const jabatan = this.getAttribute('data-jabatan');
        
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_jenis_kelamin').value = jenis_kelamin;
        document.getElementById('edit_jabatan').value = jabatan;
    });
});

// Handle delete button clicks
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        document.getElementById('confirmDelete').href = `includes/process_pegawai.php?delete=${id}`;
    });
});

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let found = false;
        const cells = row.querySelectorAll('td');
        
        cells.forEach(cell => {
            if (cell.textContent.toLowerCase().includes(searchValue)) {
                found = true;
            }
        });
        
        if (found) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Handle entries per page change
document.getElementById('entriesPerPage').addEventListener('change', function() {
    // In real application, this would reload the page or fetch new data
    // For demo purposes, we'll just show an alert
    alert(`Changed entries per page to: ${this.value}`);
});