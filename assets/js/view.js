let addCommentBtn = document.querySelector(".add-comment-btn");
let postMenuBtn = document.querySelector(".post-menu-btn");
let addCommentModal = document.querySelector(".add-comment-modal");
let postMenuModal = document.querySelector(".post-menu-modal");

if (addCommentModal != null) {

    let closeCommentModalBtn = addCommentModal.querySelector(".close-btn");

    addCommentBtn.addEventListener("click", function () {
        addCommentModal.classList.remove('hidden');
        addCommentModal.classList.add('flex');
    });

    closeCommentModalBtn.addEventListener('click', function () {
        addCommentModal.classList.remove('flex');
        addCommentModal.classList.add('hidden');
    });
}

if (postMenuModal != null) {

    let closePostMenuModalBtn = postMenuModal.querySelector(".close-btn");
    let postDeleteModal = document.querySelector(".post-delete-modal");
    let closePostDeleteModalBtn = postDeleteModal.querySelector(".close-btn");
    let editBlogModal = document.querySelector(".edit-blog-modal");
    let closeEditBlogModalBtn = editBlogModal.querySelector(".close-btn");
    let editTagsbtn = editBlogModal.querySelector(".edit-tags-btn");
    let editTagsModal = document.querySelector(".edit-tags-modal");
    let closeEditTagsModalBtn = editTagsModal.querySelector(".close-btn");
    let tagSearchInput = document.getElementById('tag-search');
    let availableTags = document.querySelector('.available-tags').children;
    let blogTagsInput = document.getElementById('blog-tags');
    let tagsCountElement = document.querySelector('.tags-count');
    let tagsCount = 0;

    postMenuBtn.addEventListener("click", function () {
        postMenuModal.classList.remove('hidden');
        postMenuModal.classList.add('flex');
    });

    closePostMenuModalBtn.addEventListener('click', function () {
        postMenuModal.classList.remove('flex');
        postMenuModal.classList.add('hidden');
    });

    // Delete Post Functionality

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

    // 

    // Edit Post Functionality

    document.querySelector('.modify-btn').addEventListener('click', function () {
        postMenuModal.classList.remove('flex');
        postMenuModal.classList.add('hidden');
        editBlogModal.classList.add('flex');
        editBlogModal.classList.remove('hidden');
    });

    closeEditBlogModalBtn.addEventListener('click', function () {
        editBlogModal.classList.remove('flex');
        editBlogModal.classList.add('hidden');
        postMenuModal.classList.add('flex');
        postMenuModal.classList.remove('hidden');
    });

    // Edit Tags Functionality

    /* Open Edit tags modal */

    editTagsbtn.addEventListener('click', function () {
        editTagsModal.classList.add('flex');
        editTagsModal.classList.remove('hidden');
        editBlogModal.classList.remove('flex');
        editBlogModal.classList.add('hidden');
    });

    /* Close edit tags modal */

    closeEditTagsModalBtn.addEventListener('click', function () {
        editTagsModal.classList.remove('flex');
        editTagsModal.classList.add('hidden');
        editBlogModal.classList.add('flex');
        editBlogModal.classList.remove('hidden');
    });

    /* Search for tags */

    tagSearchInput.addEventListener('keyup', function () {
        for (let tag of availableTags){
            if (tag.textContent.toLowerCase().search(this.value.toLowerCase()) >= 0) {
                tag.classList.remove('hidden');
            } else {
                tag.classList.add('hidden');
            }
        }
    });

    // Mark added tags

    let addedTags = blogTagsInput.value.split(';');

    addedTags.pop();        // Remove the last item (empty item)
    tagsCount = addedTags.length;

    for(let item of addedTags) {
        let targetTag = availableTags[0].parentElement.querySelector(`[data-id="${item}"]`);
        targetTag.classList.remove('bg-gray-100');
        targetTag.classList.add('bg-gray-700', 'text-white');
    }

    /* Add/Remove tags on click */

    for(let tag of availableTags) {

        tag.addEventListener('click', function () {
            if (this.classList.contains('bg-gray-100')) {
                this.classList.remove('bg-gray-100');
                this.classList.add('bg-gray-700', 'text-white');
                blogTagsInput.value += this.dataset.id + ';';
                tagsCount++;
            } else {
                this.classList.add('bg-gray-100');
                this.classList.remove('bg-gray-700', 'text-white');
                blogTagsInput.value = blogTagsInput.value.replace(this.dataset.id + ';', '');
                tagsCount--;
            }
            tagsCountElement.textContent = tagsCount;
        })
    }
}

