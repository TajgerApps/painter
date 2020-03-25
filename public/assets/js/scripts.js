document.addEventListener("DOMContentLoaded", function(){
    $(document).on('click', '#addDirectory', () => {
        const dirname = $('#addDirectoryModalInput').val();
        const currentPath = $('#addDirectoryModalInputCurrentPath').val();

        $.post({
            url: '/catalog/addDirectory',
            data: {
                'currentPath': currentPath,
                'dirname': dirname
            }
        });
        $('#addDirectoryModal').modal('hide');
    });
});
