let addCommentBtn = document.querySelector(".add-comment-btn");
let postMenuBtn = document.querySelector(".post-menu-btn");
let addCommentModal = document.querySelector(".add-comment-modal");
let postMenuModal = document.querySelector(".post-menu-modal");

// if (addCommentModal != null) {

//     let closeCommentModalBtn = addCommentModal.querySelector(".close-btn");

//     addCommentBtn.addEventListener("click", function () {
//         addCommentModal.classList.remove('hidden');
//         addCommentModal.classList.add('flex');
//     });

//     closeCommentModalBtn.addEventListener('click', function () {
//         addCommentModal.classList.remove('flex');
//         addCommentModal.classList.add('hidden');
//     });
// }

if (postMenuModal != null) {

    let closePostMenuModalBtn = postMenuModal.querySelector(".close-btn");
    let postDeleteModal = document.querySelector(".post-delete-modal");
    let closePostDeleteModalBtn = postDeleteModal.querySelector(".close-btn");

    postMenuBtn.addEventListener("click", function () {
        postMenuModal.classList.remove('hidden');
        postMenuModal.classList.add('flex');
    });

    closePostMenuModalBtn.addEventListener('click', function () {
        postMenuModal.classList.remove('flex');
        postMenuModal.classList.add('hidden');
    });

    document.querySelector('.delete-btn').addEventListener('click', function () {
        postMenuModal.classList.remove('flex');
        postMenuModal.classList.add('hidden');
        postDeleteModal.classList.add('flex');
        postDeleteModal.classList.remove('hidden');
    });

    closePostDeleteModalBtn.addEventListener('click', function () {
        postMenuModal.classList.add('flex');
        postMenuModal.classList.remove('hidden');
        postDeleteModal.classList.remove('flex');
        postDeleteModal.classList.add('hidden');
    });
}

