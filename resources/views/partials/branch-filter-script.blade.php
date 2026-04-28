<script>
    // Initialize branch filter from user's session/browser
    document.addEventListener('DOMContentLoaded', function() {
        const user = @json(auth()->user());
        
        // For non-owners, lock branch to their assigned branch
        if (user && user.branch && !user.is_owner && user.role !== 'owner') {
            localStorage.setItem('selectedBranch', JSON.stringify([user.branch]));
            document.querySelectorAll('.branch-link').forEach(link => {
                if (link.getAttribute('data-branch') !== user.branch) {
                    link.style.opacity = '0.5';
                    link.style.cursor = 'not-allowed';
                    link.style.pointerEvents = 'none';
                }
            });
        }

        // Sync branch filter to session for server-side use
        const selectedBranch = localStorage.getItem('selectedBranch');
        if (selectedBranch) {
            fetch('{{ route("set-branch-filter") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    branches: JSON.parse(selectedBranch)
                })
            }).catch(() => {}); // Silent fail if endpoint doesn't exist
        }
    });
</script>
