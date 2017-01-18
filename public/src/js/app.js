var postId = 0;
var postBodyElement = null;

$('.post').find('.interaction').find('.edit').on('click', function (event) {
    event.preventDefault();
    //alert('edit');
    postTitleElement = event.target.parentNode.parentNode.childNodes[1];
    postBodyElement = event.target.parentNode.parentNode.childNodes[3];
    var postBody = postBodyElement.textContent;
    var postTitle = postTitleElement.textContent;
    postId = event.target.parentNode.parentNode.dataset['postid'];
    $('#post-body').val(postBody);
    $('#post-title').val(postTitle);
    $('#edit-modal').modal();
});

$('.profile').on('click', function (event) {
    event.preventDefault();
    //alert('edit');
    $('#profile-modal').modal();
});

$('#modal-save').on('click', function () {
    $.ajax({
            method: 'POST',
            url: urlEdit,
            data: {body: $('#post-body').val(),title: $('#post-title').val(), postId: postId, _token: token}
        })
        .done(function (msg) {
            $(postBodyElement).text(msg['new_body']);
            $(postTitleElement).text(msg['new_title']);
            $('#edit-modal').modal('hide');
        });
});

$('.like').on('click', function(event) {
    event.preventDefault();
    postId = event.target.parentNode.parentNode.dataset['postid'];
    var isLike = event.target.previousElementSibling == null;
    $.ajax({
        method: 'POST',
        url: urlLike,
        data: {isLike: isLike, postId: postId, _token: token}
    })
        .done(function() {
            event.target.innerText = isLike ? event.target.innerText == 'Like' ? 'You like this post' : 'Like' : event.target.innerText == 'Dislike' ? 'You don\'t like this post' : 'Dislike';
            if (isLike) {
                event.target.nextElementSibling.innerText = 'Dislike';
            } else {
                event.target.previousElementSibling.innerText = 'Like';
            }
        });
});

$('.response').find('.rinteraction').find('.rlike').on('click', function(event) {
    event.preventDefault();
    replyId = event.target.parentNode.parentNode.dataset['replyid'];
    var isrLike = event.target.previousElementSibling == null;
    
    $.ajax({
       
        method: 'POST',
        url: urlrLike,
        data: {isrLike: isrLike, replyId: replyId, _token: token}
    })
        .done(function() {
            event.target.innerText = isrLike ? event.target.innerText == 'Like' ? 'You like this post' : 'Like' : event.target.innerText == 'Dislike' ? 'You don\'t like this post' : 'Dislike';
            if (isrLike) {
                event.target.nextElementSibling.innerText = 'Dislike';
            } else {
                event.target.previousElementSibling.innerText = 'Like';
            }
        });
});