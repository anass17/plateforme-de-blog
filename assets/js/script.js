let addBlogModal = document.querySelector('.add-blog-modal');
let addTagsModal = document.querySelector('.add-tags-modal');
let closeAddBlogModalBtn = addBlogModal.querySelector('.close-btn');
let addBlogBtn = document.querySelector('.add-blog-btn');
let addTagsBtn = document.querySelector('.add-tags-btn');
let blogTagsInput = document.getElementById('blog-tags');
let tagSearchInput = document.getElementById('tag-search');
let tagsCountElement = document.querySelector('.tags-count');
let tagsCount = 0;

// Open Add Blog Modal

addBlogBtn.addEventListener('click', function () {
    addBlogModal.classList.add('flex');
    addBlogModal.classList.remove('hidden');
});

// Close Add Blog Modal

closeAddBlogModalBtn.addEventListener('click', function () {
    addBlogModal.classList.add('hidden');
    addBlogModal.classList.remove('flex');
});

if (addTagsModal != null) {

    let closeAddTagsModalBtn = addTagsModal.querySelector('.close-btn');
    let availableTags = document.querySelector('.available-tags').children;

    // Open Add Blog Tags Modal

    addTagsBtn.addEventListener('click', function () {
        addTagsModal.classList.add('flex');
        addTagsModal.classList.remove('hidden');

        addBlogModal.classList.add('hidden');
        addBlogModal.classList.remove('flex');
    });

    // Close Add Blog Tags Modal

    closeAddTagsModalBtn.addEventListener('click', function () {
        addTagsModal.classList.add('hidden');
        addTagsModal.classList.remove('flex');
    
        addBlogModal.classList.add('flex');
        addBlogModal.classList.remove('hidden');
    });

    // Search for tags

    tagSearchInput.addEventListener('keyup', function () {
        for (let tag of availableTags){
            if (tag.textContent.toLowerCase().search(this.value.toLowerCase()) >= 0) {
                tag.classList.remove('hidden');
            } else {
                tag.classList.add('hidden');
            }
        }
    });

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

    
    function createErrorMsg(element, errorMsg) {
        let msg = document.createElement('p');

        msg.textContent = errorMsg;

        msg.className = "text-red-500 mt-1";

        element.after(msg);
    }

    function clearError(elements) {
        elements.forEach(item => {
            if (item.nextElementSibling) {
                item.nextElementSibling.remove();
            }
        })
    }

    document.getElementById('add-post-submit').addEventListener('click', function (e) {
        let inputs = addBlogModal.querySelectorAll('input, textarea');
        let hasErrors = false;

        clearError(inputs);

        if (inputs[0].value.length < 3) {
            createErrorMsg(inputs[0], "Title is too short");
            hasErrors = true;
        }

        if (inputs[1].value.length < 20) {
            createErrorMsg(inputs[1], "Body is too short");
            hasErrors = true;
        }

        if (inputs[2].value.length < 2) {
            createErrorMsg(inputs[2], "Please add at least one tag");
            hasErrors = true;
        }

        if (hasErrors == true) {
            e.preventDefault();
        }
    });

}