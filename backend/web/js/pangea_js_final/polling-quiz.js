function drag() {

    const fills = document.querySelectorAll('.fill');
    const empties = document.querySelectorAll('.empty');
    const content = document.querySelector('.content');


    // Fill listeners

    fills.forEach(function (fill) {
        fill.addEventListener('dragstart', dragStart);
        fill.addEventListener('dragend', dragEnd);
    })


    // Loop through empty boxes and add listeners
    empties.forEach(function (empty) {
        empty.addEventListener('dragover', dragOver);
        empty.addEventListener('dragenter', dragEnter);
        empty.addEventListener('dragleave', dragLeave);
        empty.addEventListener('drop', dragDrop);
    })



    function dragStart(e) {


        const empties = document.querySelectorAll('.empty');
        empties.forEach(function (empty) {
            empty.remove();
        })




        if (this.parentElement.classList.contains("questionaire") === true) {


            const beforeFills = this.parentElement.querySelectorAll('.fill');
            const questionaires = this.parentElement;

            beforeFills.forEach(function (item) {
                var newEmpty = document.createElement("div");
                newEmpty.classList.add('empty');
                item.parentElement.insertBefore(newEmpty, item.nextElementSibling);
            });

            var newEmpty = document.createElement("div");
            newEmpty.classList.add('empty');
            questionaires.prepend(newEmpty);

            if (this.nextElementSibling.classList.contains('empty') === true) {
                this.nextElementSibling.remove();
            };
            if (this.previousElementSibling.classList.contains('empty') === true) {
                this.previousElementSibling.remove();
            };

        } else {

            const beforeFills = document.querySelectorAll('.quiz-container .fill');
            const questionaires = document.querySelectorAll('.questionaire');

            beforeFills.forEach(function (item) {
                var newEmpty = document.createElement("div");
                newEmpty.classList.add('empty');
                item.parentElement.insertBefore(newEmpty, item.nextElementSibling);
            });

            questionaires.forEach(function (item) {
                var newEmpty = document.createElement("div");
                newEmpty.classList.add('empty');
                item.prepend(newEmpty);
            });

        }

        const newEmpties = document.querySelectorAll('.empty');

        newEmpties.forEach(function (empty) {
            empty.addEventListener('dragover', dragOver);
            empty.addEventListener('dragenter', dragEnter);
            empty.addEventListener('dragleave', dragLeave);
            empty.addEventListener('drop', dragDrop);
        })



        this.className += ' hold';


        var cloneContent = this.cloneNode(true);
        cloneContent.classList.remove('hold');
        content.append(cloneContent);



        if (this.parentElement.parentElement.classList.contains('quiz-container') == true) {
            setTimeout(() => (this.className = 'invisible'), 0);
        }


    }

    function dragEnd() {
        console.log('dragEnd');
        this.className = 'fill';

        const invisible = document.querySelectorAll('.invisible');

        invisible.forEach(function (item) {
            item.classList.remove('invisible');
        })

        content.innerHTML = "";

    }

    function dragOver(e) {
        e.preventDefault();
    }

    function dragEnter(e) {
        e.preventDefault();
        this.className += ' hovered';
    }

    function dragLeave() {
        this.className = 'empty';

    }

    function dragDrop() {



        content.firstElementChild.firstElementChild.classList.add('d-none');
        content.querySelector('.que').classList.remove('d-none');
        content.firstElementChild.lastElementChild.classList.remove('d-none');


        this.parentElement.insertBefore(content.firstElementChild, this);
        content.innerHTML = "";

        order();

        const empties = document.querySelectorAll('.empty');
        empties.forEach(function (empty) {
            empty.remove();
        })


        const invisible = document.querySelectorAll('.invisible');
        //console.log(invisible);
        invisible.forEach(function (item) {
            //item.classList.remove('invisible');
            item.remove();
        })

        drag();
    }

};

drag();



var container = document.querySelector('.quiz-container');

container.addEventListener('click', function (e) {

    //if (e.target.tagName.toLowerCase() === 'div') {
    //    console.log(e.target);
    //};

    if (e.target.tagName.toLowerCase() === 'button') {

        switch (e.target.name) {

            case "delete-que":

                var topic = e.target.parentElement.parentElement.parentElement;
                topic.remove();
                order();

                break;

            case "add-que":

                var topic = e.target.parentElement.parentElement.parentElement;
                var cloneTopic = topic.cloneNode(true);

                topic.parentElement.insertBefore(cloneTopic, topic.nextElementSibling);
                order();

                break;


            case "add-new-que":

                var topic = e.target.parentElement.parentElement.parentElement;

                console.log(topic);
                var cloneTopic = topic.cloneNode(true);

                cloneTopic.querySelectorAll('button[name=delete-li]').forEach(function (item) {
                    item.parentElement.remove();
                })

                var option = cloneTopic.querySelector('#answerOptions option[value="0"]');
                option.setAttribute("selected", "true");

                cloneTopic.querySelector('.add-option').classList.add('d-none');

                cloneTopic.querySelector('input[name=topic]').value = '';
                cloneTopic.querySelector('input[name=topic]').placeholder = 'Please fill in the topic';

                if (cloneTopic.querySelector('button[name=necessary]').classList.contains('que-necessary')) {
                    cloneTopic.querySelector('button[name=necessary]').classList.remove('que-necessary');

                    if (cloneTopic.querySelector('.nec') != null) {
                        cloneTopic.querySelector('.nec').remove();
                    }
                }

                topic.parentElement.insertBefore(cloneTopic, topic.nextElementSibling);
                order();

                break;


            case "edit-que":

                var topic = e.target.parentElement.parentElement;

                topic.draggable ? (
                    topic.draggable = false
                ) : (
                    topic.draggable = true
                )

                //topic.draggable == 'false';
                //console.log(topic.draggable);


                var que = e.target.parentElement.parentElement.querySelector('.que');

                que.classList.contains('edit') ? (
                    que.classList.remove('edit')
                ) : (
                    que.classList.add('edit')
                )


                topic.querySelectorAll('textarea,input,select').forEach(function (item) {
                    if (item.type !== 'button') {
                        item.disabled ? (
                            item.disabled = false
                        ) : (
                            item.disabled = true
                        )
                    };
                });

                var select = topic.querySelector('#answerOptions');
                select.classList.contains('d-none') ? (
                    select.classList.remove('d-none')
                ) : (
                    select.classList.add('d-none')
                )

                if (e.target.classList.contains('fa-pencil')) {
                    e.target.classList.remove('fa-pencil');
                    e.target.classList.add('fa-check-circle');
                    e.target.style.backgroundColor = "#4aaab3";
                    e.target.title = "Confirm editing";
                } else {
                    e.target.classList.remove('fa-check-circle');
                    e.target.classList.add('fa-pencil');
                    e.target.style.backgroundColor = "#327a2a";
                    e.target.title = "Edit";
                };

                break;

            case "delete-topic":

                var allTopic = e.target.parentElement.parentElement;
                var questionnaire = e.target.parentElement.parentElement.nextElementSibling;
                allTopic.remove();
                questionnaire.remove();

                break;

            case "add-topic":

                var allTopic = e.target.parentElement.parentElement;
                var cloneAllTopic = allTopic.cloneNode(true);
                var questionnaire = e.target.parentElement.parentElement.nextElementSibling;
                var cloneQuestionnaire = questionnaire.cloneNode(true);

                allTopic.parentElement.insertBefore(cloneQuestionnaire, questionnaire.nextElementSibling);
                allTopic.parentElement.insertBefore(cloneAllTopic, questionnaire.nextElementSibling);
                order();

                break;

            case "edit-topic":

                var topic = e.target.parentElement.parentElement;


                topic.querySelectorAll('textarea,input,select').forEach(function (item) {
                    if (item.type !== 'button') {
                        item.disabled ? (
                            item.disabled = false
                        ) : (
                            item.disabled = true
                        )
                    };
                });

                break;

            case "delete-li":

                e.target.parentElement.remove();
                break;

            case "necessary":

                var que = e.target.parentElement.parentElement;

                if (e.target.classList.contains('que-necessary')) {
                    e.target.classList.remove('que-necessary');

                    if (e.target.parentElement.querySelector('.nec') != null) {
                        e.target.parentElement.querySelector('.nec').remove();
                    }


                } else {

                    e.target.classList.add('que-necessary');
                    var topic = e.target.parentElement.querySelector('input[name=topic]');
                    var n = "<p style='color:red' class='nec'>*<p>";
                    topic.insertAdjacentHTML("beforeBegin", n);

                    que.querySelectorAll('input[type=radio],input[type=checkbox]').forEach(function (item) {
                        item.setAttribute('required', 'required');

                    })
                }



                break;
        }
    };


    if (e.target.tagName.toLowerCase() === 'a') {

        var cloneli = e.target.parentElement.previousElementSibling.cloneNode(true);
        cloneli.querySelector('input:last-child').value = "";
        cloneli.querySelector('input:last-child').placeholder = "Please fill in the options";
        //console.log(cloneli);

        e.target.parentElement.parentElement.insertBefore(cloneli, e.target.parentElement);
    };



}, false);





function order() {

    const questionaires = document.querySelectorAll('.questionaire');

    questionaires.forEach(function (item) {
        const fill = item.querySelectorAll('.fill');

        for (i = 0; i < fill.length; i++) {
            var x = (i + 1) + ".";
            fill[i].querySelector('.question .num').innerText = x;
        }

    })

}

order();





document.querySelectorAll('.questionaire .question').forEach(function (item) {
    var value = item.querySelector('#answerOptions').name;
    var option = item.querySelector('option[value="' + value + '"]');
    option.setAttribute("selected", "true");
    //console.log(option);
    //console.log(item.querySelector('option[name='+value+']'));

})


function optionChange(select) {
    var que = select.parentElement.parentElement;
    que.querySelectorAll('button[name=delete-li]').forEach(function (item) {
        item.parentElement.remove();
    })


    var addOption = que.querySelector('.add-option');
    switch (select.selectedIndex) {
        case 0:
            //addOption.parentElement.insertAdjacentHTML("beforeBegin", insertHTML);
            //if (!addOption.classList.contains('d-none')) {
            //    addOption.classList.add('d-none');
            //}


            break;

        case 1:
            var insertHTML = "<li><input type=radio id=newRadio disabled/><label for=newRadio> <input type=text placeholder=Please fill in the options /> </label><button name=delete-li title=Please fill in the options class='btn far fa-times-circle'></button></li>"
            //addOption.parentElement.parentElement.innerHTML += insertHTML;

            addOption.parentElement.insertAdjacentHTML("beforeBegin", insertHTML);
            if (addOption.classList.contains('invisible')) {
                addOption.classList.remove('invisible')
            }
            break;

        case 2:
            var insertHTML = "<li><input type=radio id=newRadio disabled/><label for=newRadio> <input type=text placeholder=Please fill in the options /> </label><button name=delete-li title=Please fill in the options class='btn far fa-times-circle'></button></li>"
            //addOption.parentElement.parentElement.innerHTML += insertHTML;

            addOption.parentElement.insertAdjacentHTML("beforeBegin", insertHTML);
            if (addOption.classList.contains('invisible')) {
                addOption.classList.remove('invisible')
            }
            break;

        case 3:
            var insertHTML = "<li><span class='fa fa-star'></span> <button name=delete-li title=Please fill in the options class='btn far fa-times-circle'></button></li>"
            //addOption.parentElement.parentElement.innerHTML += insertHTML;

            addOption.parentElement.insertAdjacentHTML("beforeBegin", insertHTML);
            if (addOption.classList.contains('invisible')) {
                addOption.classList.remove('invisible');
            }
            break;

        case 4:
            var insertHTML = "<li><input type=radio id= disabled/> <input type=radio id= disabled/> <button name=delete-li class='btn far fa-times-circle'></button></li>"
            //addOption.parentElement.parentElement.innerHTML += insertHTML;

            addOption.parentElement.insertAdjacentHTML("beforeBegin", insertHTML);
            if (addOption.classList.contains('invisible')) {
                addOption.classList.remove('invisible');
            }

            break;

        case 5:
            var insertHTML = "<li><input type=radio id=newRadio disabled/><label for=newRadio> <input type=text placeholder=Please fill in the options /> </label><button name=delete-li title=Please fill in the options class='btn far fa-times-circle'></button></li>"
            //addOption.parentElement.parentElement.innerHTML += insertHTML;

            addOption.parentElement.insertAdjacentHTML("beforeBegin", insertHTML);
            if (addOption.classList.contains('invisible')) {
                addOption.classList.remove('invisible')
            }
            break;

        case 6:
            var insertHTML = "<li><textarea rows=5></textarea><button name=delete-li class='btn far fa-times-circle d-none'></button></li>"
            //addOption.parentElement.parentElement.innerHTML += insertHTML;

            addOption.parentElement.insertAdjacentHTML("beforeBegin", insertHTML);
            if (!addOption.classList.contains('invisible')) {
                addOption.classList.add('invisible');
            }
            break;

        case 7:
            var insertHTML = "<li><input type=radio id=newRadio disabled/><label for=newRadio> <input type=text placeholder=Please fill in the options /> </label><button name=delete-li title=Please fill in the options class='btn far fa-times-circle'></button></li>"
            //addOption.parentElement.parentElement.innerHTML += insertHTML;

            addOption.parentElement.insertAdjacentHTML("beforeBegin", insertHTML);
            if (addOption.classList.contains('invisible')) {
                addOption.classList.remove('invisible')
            }
            break;
        default:
            break;
    }


}