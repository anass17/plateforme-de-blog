let addCommentBtn = document.querySelector(".add-comment-btn");
let addCommentModal = document.querySelector(".add-comment-modal");
let closeCommentModalBtn = addCommentModal.querySelector(".close-btn");

if (addCommentBtn != null) {
    addCommentBtn.addEventListener("click", function () {
        addCommentModal.classList.remove('hidden');
        addCommentModal.classList.add('flex');
    });
}

if (addCommentModal != null) {
    closeCommentModalBtn.addEventListener('click', function () {
        addCommentModal.classList.remove('flex');
        addCommentModal.classList.add('hidden');
    });
}