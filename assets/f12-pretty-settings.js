const f12PrettySettings = {
    isChanged: false,
    appendNewElement(elem) {
        const button = $(elem);
        const subSettingsBlock = button.parent();
        const newBlock = $(subSettingsBlock.find('.f12-settings-block:last-child .f12-sub-settings').prop('outerHTML'));
        const index = newBlock.data('index');
        const nextIndex = index + 1;
        newBlock.attr('data-index', nextIndex);
        newBlock.find('.element-num').html(nextIndex);
        newBlock.find('input').each((key, element) => {
            element = $(element);
            element.attr('name', element.attr('name').replace('_' + index + '_', '_' + nextIndex + '_'));
        });
        subSettingsBlock.append(newBlock);
    },
    setChanged() {
        $('.f12-settings-page').addClass('changed');
    },
    save() {
        const data = $('form.f12-settings-content').serialize();
        $.ajax({
            url: 'save',
            method: 'post',
            data: data,
            success: (response) => {
                $('.f12-settings-page').removeClass('changed');
            }
        })
    },
    cancel() {
        document.location.href = document.location.href;
    }
}


$(document).on('change', '.f12-settings-content input', () => {
    f12PrettySettings.setChanged()
});
$(document).on('keyup', '.f12-settings-content input', () => {
    f12PrettySettings.setChanged()
});