function addRow(id, headline) {

    //get element on which form field should be hooked
    const element = document.getElementById(id);

    //check if element already has children in order to give them all unique ids
    let index = 0;
    if (element.children.length > 0) {
        index = element.children.length;
    }

    //create necessary elements
    const div = document.createElement('div');
    const label = document.createElement('label');
    const input = document.createElement('input');
    const span = document.createElement('span');
//todo:class is-invalid in input einfügen, value in input einfügen, value in span wahrscheinlich einfügen

    // add necessary classes and attributes
    div.classList.add('form-group');

    label.innerText = headline + ' ' + (index + 1);

    input.setAttribute('type', 'text');
    input.setAttribute('name', id + '[' + index + ']');
    input.setAttribute('value', '');
    input.classList.add('form-control');

    span.innerText = '<?php echo $errorsArray["' + id + '"]; ?>';
    span.classList.add('invalid-feedback');

    // add to div
    div.appendChild(label);
    div.appendChild(input);
    div.appendChild(span);
    //add to DOM
    element.appendChild(div);

    //Es sollen nur bis zu 15 Felder hinzugefügt werden können
    if (element.children.length > 14) {
        const span = document.createElement('Span')
        span.innerText = 'Es können nur 15 Felder hinzugefügt werden';
        const button = document.getElementById(id + 'Button');
        button.setAttribute('disabled', 'disabled')
        element.appendChild(span);
    }

}

