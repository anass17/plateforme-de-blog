let profileOptions = document.querySelectorAll('.profile-options button');
let optionBlocks = document.getElementById('option-blocks');
let editProfileBtn = document.querySelector('.edit-profile-btn');
let editProfileCancel = document.querySelector('.edit-profile-cancel');
let profileForm = document.getElementById('profile-form');
let buttonsContainer = profileForm.querySelector('.buttons');
let imageOverlay = profileForm.querySelector('.image-overlay');
let inputs = profileForm.querySelectorAll('input');

// Switch Profile Tabs

profileOptions.forEach(option => {
    option.addEventListener('click', function () {
        let target = this.dataset.target;

        for (option of optionBlocks.children) {
            option.classList.add('hidden');
        }
        optionBlocks.querySelector(`#${target}`).classList.remove('hidden');
    });
});

// Enable Profile Edit

editProfileBtn.addEventListener('click', function () {

    buttonsContainer.firstElementChild.classList.add('hidden');
    buttonsContainer.lastElementChild.classList.remove('hidden');

    imageOverlay.classList.add('flex');
    imageOverlay.classList.remove('hidden');

    inputs.forEach(item => {
        item.removeAttribute('disabled');
        item.classList.remove('border-white');
        item.classList.add('border-gray-200');
    });
});

// Cancel Profile Edit

editProfileCancel.addEventListener('click', function () {
    buttonsContainer.firstElementChild.classList.remove('hidden');
    buttonsContainer.lastElementChild.classList.add('hidden');

    imageOverlay.classList.remove('flex');
    imageOverlay.classList.add('hidden');

    inputs.forEach(item => {
        item.setAttribute('disabled', '');
        item.classList.add('border-white');
        item.classList.remove('border-gray-200');
    });
});