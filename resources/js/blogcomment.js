document.addEventListener('click', function(e) {
    if (e.target.classList.contains('submit-comment')) {
        e.preventDefault();
        var form = e.target.closest('form');
        var postuuid = form.querySelector('.postuuid').value;
        var author = form.querySelector('.username').value;
        var comment = form.querySelector('.comment').value;

        axios.post('/blog-comment', {
            postuuid: postuuid,
            author: author,
            comment: comment,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        })
            .then(function(response) {
                console.log(response);
                if (response.status === 200) {
                    location.reload();
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }
});