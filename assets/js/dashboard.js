let dashboardOptions = document.querySelectorAll('.dashboard-options button');
let optionBlocks = document.getElementById('option-blocks');

dashboardOptions.forEach(option => {
    option.addEventListener('click', function () {
        let target = this.dataset.target;

        for (option of optionBlocks.children) {
            option.classList.add('hidden');
        }
        optionBlocks.querySelector(`#${target}`).classList.remove('hidden');
    });
});