const usernames = document.querySelectorAll(".username");
usernames.forEach((name, index) => {
    name.addEventListener('click', (event) => { //jedem Namen wird ein EventListener gegeben.
        getRow(index);

    })
})


function getRow(index) {
    let row = [];
    let skills = [];
    let memberIds = [];
    let skillIds = [];
    const tr = document.querySelectorAll("tr");
    tr.forEach(element => { //alle Inhalte der Spalte, auf die man gedrückt hat, werden ausgelesen
        const rowElement = element.children[index + 1].innerText;
        const skillElement = element.children[0].innerText;

        const skillId = element.children[0].id;
        const memberId = element.children[index + 1].id;

        row.push(rowElement);
        skills.push(skillElement);

        memberIds.push(memberId);
        skillIds.push(skillId); //Ids aller Skills

    })

    fillModal(memberIds, skills, row, skillIds);
}

function colorizeKnowledge(knowledge) {
    if (knowledge.classList.contains("mentor")){
        knowledge.classList.remove("mentor");
        knowledge.classList.add("help");
    } else if (knowledge.classList.contains ("help")) {
        knowledge.classList.remove("help");
    }
    else{knowledge.classList.add("mentor")}
}

function fillModal(memberIds, skills, row, skillIds) { //das Modal wird mit den Inhalten der entsprechenden Spalte befüllt
    const modaltitle = document.querySelector("#modaltitle");
    const modalbody = document.querySelector("#modalbody");
    const form = document.createElement("form");
    const div = document.createElement("div");

    div.classList.add("div_body");

    modaltitle.innerText = row[0];


    for (let i = 0; i < skills.length - 1; i++) {                   //pro Skill entsteht eine row mit dem skill und dem level (data)
        const div_skill = document.createElement('div');
        const div_data = document.createElement("div");
        const div_row = document.createElement('div');

        div_row.classList.add("div_row");

        div_skill.classList.add("div_skill");
        div_skill.innerText = skills[i + 1];
        div_data.classList.add("div_data");
        div_data.innerText = row[i + 1];

        div_row.addEventListener('click', (event) => {          //nach Klick kann das Level durch Radiobuttons bearbeitet werden
            createRadiobutton(form, div_skill, div_data, memberIds, skillIds, div_row)

        });

        div_row.appendChild(div_skill);
        div_row.appendChild(div_data);
        div.appendChild(div_row);
    }

    modalbody.appendChild(form);
    form.appendChild(div);

    document.querySelector("#changes").addEventListener('click', function () {
        saveChanges(memberIds, skillIds, skills, row);
    });

}


function createRadiobutton(form, div_skill, div_data, memberIds, skillIds, div_row) {

    form.setAttribute('method', 'POST');

    const knowledgeLevel = ["Beginnerwissen", "Fortgeschrittenes Wissen", "Expertenwissen"]
    const levelValue = ['*', '**', '***'];
    const div_form = document.createElement("div");

    div_form.classList.add("div_form");

    for (let i = 0; i < knowledgeLevel.length; i++) { //in jedes div_form kommen drei Radiobuttons

        const input = document.createElement("input");
        const label = document.createElement('label');
        const br = document.createElement("br");

        input.setAttribute('type', 'radio');
        input.setAttribute('name', 'skillLevel' + "-" + div_skill.innerText);
        input.setAttribute('value', levelValue[i]);

        if (div_data.innerText === levelValue[i]) {
            input.setAttribute('checked', 'checked');
        }

        label.innerText = knowledgeLevel[i];

        div_form.appendChild(input);
        div_form.appendChild(label);
        div_form.appendChild(br);
    }
    if (div_row.children[1] === div_data) {  //falls es schon ein Level gibt, wird es durch das Formular ersetzt
        div_row.replaceChild(div_form, div_data);
    }


}

function saveChanges(memberIds, skillIds, skills, row) {
    const skillValues = {};

    const valueArr = [];
    const idSkillArr = [];
    const idNameArr = []

    const header = document.querySelectorAll("th");
    const forms = document.getElementsByTagName('form')[0];
    const radios = forms.querySelectorAll('input[type="radio"]');

    for (let i = 0; i < radios.length; i++) {
        if (radios[i].checked) {        //das Value des ausgewählten Radiobutton wird in das valueArr Array gepusht

            skillValues[radios[i].name] = radios[i].value;
            valueArr.push(radios[i].value);
        }
    }
    for (let values in skillValues) {
        let valueParts = values.split('-');
        for (let i = 1; i < skills.length; i++) {
            if (valueParts[1] === skills[i]) {      //Überprüfung welche Id der bearbeitete Skill auf der Übersichtsseite hat
                const skillIdParts = skillIds[i].split("-");
                idSkillArr.push(skillIdParts[1]);   //die Id kommt ins idSkillArr Array
            }
        }

    }

    for (let i = 1; i < header.length; i++) {
        if (row[0] === header[i].innerText) {   //Überprüfung welche Id der bearbeitete Nutzer auf der Übersichtsseite hat
            const nameId = header[i].getAttribute("id");
            nameIdParts = nameId.split(":");
            idNameArr.push(nameIdParts[1])  //Id kommt ins idNameArr Array

        }
    }

    //sendToDatabase(idSkillArr, valueArr, idNameArr);


}

/*function sendToDatabase(idSkillArr, valueArr, idNameArr){
    let data = {
        name: idNameArr[0],
        level: valueArr[0],
        skill: idSkillArr[0],
    };
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "skillmatrix.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
        }
    };
    xhr.send(JSON.stringify(data));

}*/



