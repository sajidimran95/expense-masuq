<script>
    document.addEventListener('DOMContentLoaded', () => {
        const roleSelect = document.querySelector('[data-role-select]');
        const permissionsBox = document.querySelector('[data-permissions-box]');

        const togglePermissions = () => {
            permissionsBox?.classList.toggle('d-none', roleSelect?.value === 'super_admin');
        };

        roleSelect?.addEventListener('change', togglePermissions);
        togglePermissions();
    });
</script>
