/* data */
let todos = []
const staticGroups = [{id: -1, name: "Все задачи"}, {id: 0, name: "Персональные"}]
let groups = []
let currentGroupId = -1;

/* on mounted */
mounted();

/* methods */
async function mounted () {
    try {
        todos = await $.ajax({ url: '/api/todo' })
        todos = todos.reverse() // ! переворачиваю массив что бы сверху отображались самые новые
        // console.log(todos)
        renderTodos()

        groups = await $.ajax({ url: '/api/group' })
        groups = groups.reverse()
        renderGroups()
    } catch (err) {
        console.log('Error!', err)
    }
}

function renderGroups() {
    // console.log()
    const allGroups = [...staticGroups, ...groups]
    let html = ''
    for (const group of allGroups) {
        if (group.id <= 0) html += `<li onclick="selectGroup(event)" data-id="${group.id}" class="${group.id === currentGroupId ? 'selected' : ''}">${group.name}</li>`
        else html+=`
        <li onclick="selectGroup(event)" data-id="${group.id}" class="${group.id === currentGroupId ? 'selected' : ''}"><button class="btn-sketched btn-rounded" onclick="deleteGroup(event)">X</button><div class="group-name" data-id="${group.id}">${strip(group.name)}</div></li>
        `
    }
    $('#group-list').html(html)
}

function renderTodos() {
    let html = ''
    const filteredTodo = todos.filter(t => (t.group_id === currentGroupId || currentGroupId === -1))
    console.log('todos',todos)
    // console.log('filtered', currentGroupId,filteredTodo)
    for (const todo of filteredTodo) {
        /* 
            передал дата атрибут всей тудушке что бы к 
            ней был доступ из любого чайлда (delete, checkbox) 
        */
        html += `
        <div class="todo ${todo.completed ? 'checked' : ''}" data-id="${todo.id}">
            <input type="checkbox" onchange="checkboxToggle(event)" ${todo.completed ? 'checked' : ''} >
            <span class="group">*${strip(todo.group_name)}</span>
            <span class="text">${strip(todo.text)}</span>
            <div class="delete" onclick="deleteTodo(event)"></div>
        </div>
        `
    }
    $('#todo-list').html(html) 
}

async function addTodo() {
    try {
        let text = $('#input-text').val();
        $('#input-text').val('')
        if(!strip(text).trim()) return
        const data = {
            text: text //text Аналогия 
        }
        
        if(currentGroupId !== 0 && currentGroupId !== -1) data.group = currentGroupId 
        const newTodo = await $.ajax({
            url: '/api/todo',
            type: 'post',
            data: data
        })
        // ! поменял push на unshift что бы новые тудушки добавлялись в начало массива
        todos.unshift(newTodo); // ? https://alligator.io/js/push-pop-shift-unshift-array-methods/

        renderTodos();
        
    } catch (error) {
        console.log('Error!', error)
    }
}

// создание новой группы
async function addGroup() {
    const groupName = await createGroupModal()
    if (!strip(groupName).trim()) return
    //ajax /api/group POST
    try {

        const newGroup = await $.ajax({
            url: '/api/group',
            type: 'post',
            data: {
                name: groupName 
            }
        })
        groups.unshift(newGroup);

        renderGroups();
        
    } catch (error) {
        console.log('Error!', error)
    }

}

async function deleteTodo({target}) {
    /* получаем тут родительскую ноду, из нее уже получаем айди */
    const id = target.parentNode.dataset.id;
    try {
        todos = todos.filter((todo) => todo.id != id) 
        renderTodos() 
        await $.ajax({
            url: `/api/todo?delete=${id}`
        })
    } catch (error) {
        console.log('Error!', error)
    }
}

async function deleteGroup(event) {
    /* получаем тут родительскую ноду, из нее уже получаем айди */
    const id = event.target.parentNode.dataset.id;
    try {
        await $.ajax({
            url: `/api/group?delete=${id}`
        })
        groups = groups.filter((group) => group.id != id) 
        // renderGroups() 
        todos = todos.filter(t => t.group_id != id)
        
        currentGroupId = -1; //при удалении группы переводит на "Все задачи"
        renderTodos()
        renderGroups() 
    } catch (error) {
        console.log('Error!', error)
    }
}


function checkboxToggle(event) {
    const parent = event.target.parentNode;
    const id = parent.dataset.id;
    const state = event.target.checked; // состояние чекбокса вкл\выкл
    if (state) $(parent).addClass('checked') 
    else $(parent).removeClass('checked')

    for(let i=0; i<todos.length;i++) {
        if(todos[i].id == id) {
            todos[i].completed = state;            
        }
    }

    // console.log(id, state) // ajax /api/todo PUT
    $.ajax ({
        url: `/api/todo`,
        type: 'PUT',
        data: {
            id: id,
            state: state
        }
    })
}
function selectGroup(event) {
    // if (event.target)
    const id = event.target.dataset.id
    // console.log('select', id)
    // console.log('select', event.target)

    if (!id) return
    currentGroupId = Number(id)
    // console.log('render', currentGroupId)
    renderGroups()
    renderTodos()
}

/* модалка добавления группы */
function createGroupModal() {
    const modal = $('#modal')
    /* аннимируем появление модалки */
    modal.animate({opacity: 1}, 300);
    modal.removeClass('hidden')

    /* 
        возвращаем промис в котором ожидаем ввода 
        от пользователя, либо закрытия модали.
        Если пользователь просто закрывает модалку
        она возвращает null, в противном случае
        возвращает значение из инпута
        пы. сы. 
        логика не сложная, главное в промисах разобраться
    */
    return new Promise((resolve) => {
        const addButton = $('#modal-add')
        const input = $('#modal-input')

        /* функция закрытия модалки что бы не повторять код */
        const closeModal = () => {
            modal.animate({opacity: 0}, 300);
            setTimeout(() => {
                modal.addClass('hidden')
            }, 300)

            /* 
                когда мы делаем .on мы подписываемся на событие нажатия.
                если что то пропадает и появляется опять (как в нашем случае модалка)
                очень важно отписываться от событий что бы не допускать утечек памяти
                (проще говоря если бы мы не отписывались, оно каждый раз при запуске
                модалки подписывалось бы заного и если будет оооч много подписок, 
                во первых одна и та же логика будет запускаться кучу раз, во вторых это
                большой удар по произовадительности)
            */
            modal.off()
            addButton.off()
        }
        modal.on('click', ({target}) => {
            if (target.id === 'modal') {
                /* функция resolve завершает наш промис 
                дая понять await что больше ждать не нужно */
                resolve(null)  
                closeModal()
            }
        })
        addButton.on('click', () => {
            const groupName = input.val()
            if (!groupName) return
            input.val('')
            resolve(groupName)
            closeModal()
        })
    })
}

function strip(text) {
    // console.log(text)
    return text.replace(/<[^>]*>?/gm, '');
}

function logout () {
    window.location.replace("/logout");
}

function logoRedirect () {
    window.location.replace("/index");
}

/* binding */
$('#logout').on('click', logout);
$('#logo').on('click', logoRedirect);
$('#add-todo').on('click', addTodo);
$('#add-group').on('click', addGroup);

/* добавление тудушки при нажатии кнопки enter */
// обрабатываем нажатие на любую кнопку в поле #input-text
$('#input-text').keydown((e) => {
    // получаем уникальны код кнопки
    let keyCode = (e.keyCode ? e.keyCode : e.which);
    // проверяем соответствует ли код уникальному коду кнопки enter
    if (keyCode == 13) { 
        $('#add-todo').trigger('click');
        $('#modal-add').trigger('click'); // нажимаем кнопку #add-button автоматически)
    }
});

$('#modal-input').keydown((e) => {
    // получаем уникальны код кнопки
    let keyCode = (e.keyCode ? e.keyCode : e.which);
    // проверяем соответствует ли код уникальному коду кнопки enter
    if (keyCode == 13) { 
        $('#modal-add').trigger('click'); // нажимаем кнопку #add-button автоматически)
    }
});

