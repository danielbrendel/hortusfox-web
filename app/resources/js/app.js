/**
 * app.js
 * 
 * Put here your application specific JavaScript implementations
 */

import './../sass/app.scss';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.constChatMessageQueryRefreshRate = 1000 * 15;
window.constChatUserListRefreshRate = 1000 * 15;
window.constChatTypingRefreshRate = 2000;

window.vue = new Vue({
    el: '#app',

    data: {
        bShowAddPlant: false,
        bShowEditText: false,
        bShowEditBoolean: false,
        bShowEditInteger: false,
        bShowEditDate: false,
        bShowEditCombo: false,
        bShowEditPhoto: false,
        bShowEditLinkText: false,
        bShowUploadPhoto: false,
        bShowCreateTask: false,
        bShowEditTask: false,
        bShowEditPreferences: false,
        bShowAddInventoryItem: false,
        bShowEditInventoryItem: false,
        bShowManageGroups: false,
        bShowRestorePassword: false,
        bShowCreateNewUser: false,
        bShowCreateNewLocation: false,
        bShowRemoveLocation: false,
        comboLocation: [],
        comboCuttingMonth: [],
        comboLightLevel: [],
        comboHealthState: [],
        confirmPhotoRemoval: 'Are you sure you want to remove this photo?',
        confirmPlantRemoval: 'Are you sure you want to remove this plant?',
        confirmSetAllWatered: 'Are you sure you want to update the last watered date of all these plants?',
        confirmInventoryItemRemoval: 'Are you sure you want to remove this item?',
        newChatMessage: 'New',
        currentlyOnline: 'Currently online: ',
        chatTypingEnable: false,
        chatTypingTimer: null,
        chatTypingHide: null,
        chatTypingCounter: 1
    },

    methods: {
        ajaxRequest: function (method, url, data = {}, successfunc = function(data){}, finalfunc = function(){}, config = {})
        {
            let func = window.axios.get;
            if (method == 'post') {
                func = window.axios.post;
            } else if (method == 'patch') {
                func = window.axios.patch;
            } else if (method == 'delete') {
                func = window.axios.delete;
            }

            func(url, data, config)
                .then(function(response){
                    successfunc(response.data);
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(function(){
                        finalfunc();
                    }
                );
        },

        initNavBar: function()
        {
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach( el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);

                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');
                    });
                });
            }
        },

        showEditText: function(plant, property, defval, anchor = '')
        {
            document.getElementById('inpEditTextPlantId').value = plant;
            document.getElementById('inpEditTextAttribute').value = property;
            document.getElementById('inpEditTextValue').value = defval;
            document.getElementById('inpEditTextAnchor').value = anchor;
            window.vue.bShowEditText = true;
        },

        showEditBoolean: function(plant, property, hint, defval)
        {
            document.getElementById('inpEditBooleanPlantId').value = plant;
            document.getElementById('inpEditBooleanAttribute').value = property;
            document.getElementById('property-hint').innerHTML = hint;
            document.getElementById('inpEditBooleanValue').checked = defval;
            window.vue.bShowEditBoolean = true;
        },

        showEditInteger: function(plant, property, defval)
        {
            document.getElementById('inpEditIntegerPlantId').value = plant;
            document.getElementById('inpEditIntegerAttribute').value = property;
            document.getElementById('inpEditIntegerValue').value = defval;
            window.vue.bShowEditInteger = true;
        },

        showEditDate: function(plant, property, defval)
        {
            document.getElementById('inpEditDatePlantId').value = plant;
            document.getElementById('inpEditDateAttribute').value = property;
            document.getElementById('inpEditDateValue').value = defval;
            window.vue.bShowEditDate = true;
        },

        showEditCombo: function(plant, property, combo, defval)
        {
            document.getElementById('inpEditComboPlantId').value = plant;
            document.getElementById('inpEditComboAttribute').value = property;
            
            if (typeof combo !== 'object') {
                console.error('Invalid combo specified');
                return;
            }

            let sel = document.getElementById('selEditCombo');
            if (sel) {
                for (let i = sel.options.length - 1; i >= 0; i--) {
                    sel.remove(i);
                }

                combo.forEach(function(elem, index){
                    let opt = document.createElement('option');
                    opt.value = elem.ident;
                    opt.text = elem.label;
                    sel.add(opt);
                });
            }

            document.getElementById('selEditCombo').value = defval;

            window.vue.bShowEditCombo = true;
        },

        showEditLinkText: function(plant, text, link)
        {
            document.getElementById('inpEditLinkTextPlantId').value = plant;
            document.getElementById('inpEditLinkTextValue').value = text;
            document.getElementById('inpEditLinkTextLink').value = link;
            window.vue.bShowEditLinkText = true;
        },

        showEditPhoto: function(plant, property)
        {
            document.getElementById('inpEditPhotoPlantId').value = plant;
            document.getElementById('inpEditPhotoAttribute').value = property;
            window.vue.bShowEditPhoto = true;
        },

        showPhotoUpload: function(plant)
        {
            document.getElementById('inpUploadPhotoPlantId').value = plant;
            window.vue.bShowUploadPhoto = true;
        },

        deletePhoto: function(photo, target)
        {
            if (!confirm(window.vue.confirmPhotoRemoval)) {
                return;
            }

            window.vue.ajaxRequest('post', window.location.origin + '/plants/details/gallery/photo/remove', { photo: photo }, function(response){
                if (response.code == 200) {
                    let elem = document.getElementById(target);
                    if (elem) {
                        elem.remove();
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        deletePlant: function(plant, retloc)
        {
            if (!confirm(window.vue.confirmPlantRemoval)) {
                return;
            }

            location.href = window.location.origin + '/plants/remove?plant=' + plant + '&location=' + retloc;
        },

        toggleTaskStatus: function(id)
        {
            window.vue.ajaxRequest('post', window.location.origin + '/tasks/toggle', { task: id }, function(response){
                if (response.code == 200) {
                    let elem = document.getElementById('task-item-' + id);
                    if (elem) {
                        elem.remove();
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        editTask: function(id)
        {
            document.getElementById('inpEditTaskId').value = id;
            document.getElementById('inpEditTaskTitle').value = document.getElementById('task-item-title-' + id).innerText;
            document.getElementById('inpEditTaskDescription').value = document.getElementById('task-item-description-' + id).innerText;
            document.getElementById('inpEditTaskDueDate').value = document.getElementById('task-item-due-' + id).innerText;

            window.vue.bShowEditTask = true;
        },

        updateLastWatered: function(id)
        {
            if (!confirm(window.vue.confirmSetAllWatered)) {
                return;
            }

            location.href = window.location.origin + '/plants/location/' + id + '/water';
        },

        expandInventoryItem: function(id)
        {
            let elem = document.getElementById(id);
            if (elem) {
                elem.classList.toggle('expand');
            }
        },

        incrementInventoryItem: function(id, target)
        {
            window.vue.ajaxRequest('get', window.location.origin + '/inventory/amount/increment?id=' + id, {}, function(response) {
                if (response.code == 200) {
                    let elem = document.getElementById(target);
                    if (elem) {
                        elem.innerHTML = response.amount;

                        if (response.amount == 0) {
                            elem.classList.add('is-inventory-item-empty');
                        } else {
                            elem.classList.remove('is-inventory-item-empty');
                        }
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        decrementInventoryItem: function(id, target)
        {
            window.vue.ajaxRequest('get', window.location.origin + '/inventory/amount/decrement?id=' + id, {}, function(response) {
                if (response.code == 200) {
                    let elem = document.getElementById(target);
                    if (elem) {
                        elem.innerHTML = response.amount;

                        if (response.amount == 0) {
                            elem.classList.add('is-inventory-item-empty');
                        } else {
                            elem.classList.remove('is-inventory-item-empty');
                        }
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        editInventoryItem: function(id, name, group, description)
        {
            document.getElementById('inpInventoryItemId').value = id;
            document.getElementById('inpInventoryItemName').value = name;
            document.getElementById('inpInventoryItemGroup').value = group;
            document.getElementById('inpInventoryItemDescription').value = document.getElementById(description).innerText;

            window.vue.bShowEditInventoryItem = true;
        },

        deleteInventoryItem: function(id, target)
        {
            if (!confirm(window.vue.confirmInventoryItemRemoval)) {
                return;
            }

            window.vue.ajaxRequest('get', window.location.origin + '/inventory/remove?id=' + id, {}, function(response) {
                if (response.code == 200) {
                    let elem = document.getElementById(target);
                    if (elem) {
                        elem.remove();
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        editInventoryGroupItem: function(id, what, def)
        {
            let input = prompt(what, def);

            if (input.length > 0) {
                window.vue.ajaxRequest('post', window.location.origin + '/inventory/group/edit', {
                    id: id,
                    what: what,
                    value: input
                }, function(response) {
                    if (response.code == 200) {
                        if (what === 'token') {
                            document.getElementById('inventory-group-elem-token-' + id).innerText = input;
                        } else if (what === 'label') {
                            document.getElementById('inventory-group-elem-label-' + id).innerText = input;
                        }
                    } else {
                        alert(response.msg);
                    }
                });
            }
        },

        removeInventoryGroupItem: function(id, target)
        {
            if (!confirm(window.vue.confirmInventoryItemRemoval)) {
                return;
            }

            window.vue.ajaxRequest('get', window.location.origin + '/inventory/group/remove?id=' + id, {}, function(response) {
                if (response.code == 200) {
                    let elem = document.getElementById(target);
                    if (elem) {
                        elem.remove();
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        refreshChat: function(auth_user)
        {
            window.vue.ajaxRequest('get', window.location.origin + '/chat/query', {}, function(response) {
                if (response.code == 200) {
                    response.messages.forEach(function(elem, index) {
                        document.getElementById('chat').innerHTML = window.vue.renderNewChatMessage(elem, auth_user) + document.getElementById('chat').innerHTML;
                    
                        let audio = new Audio(window.location.origin + '/snd/new_message.wav');
                        audio.onloadeddata = function() {
                            audio.play();
                        };
                    });
                }
            });

            setTimeout(window.vue.refreshChat, window.constChatMessageQueryRefreshRate);
        },

        renderNewChatMessage: function(elem, auth_user)
        {
            let chatmsgright = '';
            if (elem.userId == auth_user) {
                chatmsgright = 'chat-message-right';
            }

            let html = `
                <div class="chat-message ` + chatmsgright + `">
                    <div class="chat-message-user">
                        <div class="is-inline-block" style="color: ` + elem.chatcolor + `;">` + elem.userName + `</div>
                        <div class="chat-message-new">` + window.vue.newChatMessage + `</div>
                    </div>

                    <div class="chat-message-content">
                        <pre>` + elem.message + `</pre>
                    </div>

                    <div class="chat-message-info">
                        ` + elem.diffForHumans + `
                    </div>
                </div>
            `;

            return html;
        },

        refreshUserList: function()
        {
            window.vue.ajaxRequest('get', window.location.origin + '/user/online', {}, function(response) {
                if (response.code == 200) {
                    let target = document.getElementById('chat-user-list');
                    target.innerHTML = window.vue.currentlyOnline;

                    response.users.forEach(function(elem, index) {
                        let comma = '';
                        if (index < response.users.length - 1) {
                            comma = ', ';
                        }
                        
                        target.innerHTML += elem.name + comma;
                    });
                }
            });

            setTimeout(window.vue.refreshUserList, window.constChatUserListRefreshRate);
        },

        refreshTypingStatus: function()
        {
            if (!window.vue.chatTypingEnable) {
                return;
            }

            window.vue.ajaxRequest('get', window.location.origin + '/chat/typing/update', {}, function(response){
                if (response.code != 200) {
                    console.error(response.msg);
                }
            });

            setTimeout(function(){
                window.vue.chatTypingTimer = null;
            }, 5000);
        },

        handleChatInput: function()
        {
            if (!window.vue.chatTypingTimer) {
                window.vue.chatTypingTimer = setTimeout(function(){
                    window.vue.refreshTypingStatus();
                }, 1000);
            }
        },

        handleTypingIndicator: function()
        {
            window.vue.ajaxRequest('get', window.location.origin + '/chat/typing', {}, function(response){
                if (response.code == 200) {
                    if (response.status) {
                        let elem = document.getElementsByClassName('chat-typing-indicator')[0];
                        elem.style.display = 'block';

                        window.vue.chatTypingHide = setTimeout(window.vue.hideChatTypingIndicator, 6550);
                    }
                }
            });

            setTimeout(window.vue.handleTypingIndicator, window.constChatTypingRefreshRate);
        },

        hideChatTypingIndicator: function()
        {
            if (window.vue.chatTypingHide !== null) {
                let elem = document.getElementsByClassName('chat-typing-indicator')[0];
                elem.style.display = 'none';

                window.vue.chatTypingHide = null;
            }
        },

        animateChatTypingIndicator: function()
        {
            let indicator = document.getElementsByClassName('chat-typing-indicator')[0];
            if (indicator.style.display === 'block') {
                window.vue.removePreviousChatIndicatorCircleStyle();

                let elem = document.getElementById('chat-typing-circle-' + window.vue.chatTypingCounter.toString());
                elem.style.color = 'rgb(50, 50, 50)';
                elem.classList.add('fa-lg');

                window.vue.chatTypingCounter++;
                if (window.vue.chatTypingCounter > 3) {
                    window.vue.chatTypingCounter = 1;
                }
            }

            setTimeout(window.vue.animateChatTypingIndicator, 350);
        },

        removePreviousChatIndicatorCircleStyle: function()
        {
            let previous = window.vue.chatTypingCounter - 1;
            if (previous == 0) {
                previous = 3;
            }

            let elem = document.getElementById('chat-typing-circle-' + previous.toString());
            if (elem.classList.contains('fa-lg')) {
                elem.classList.remove('fa-lg');
                elem.style.color = 'inherit';
            }
        },

        textFilterElements: function(token) {
            let elems = document.getElementsByClassName('plant-card-title');
            for (let i = 0; i < elems.length; i++) {
                if (!elems[i].innerText.toLowerCase().includes(token.toLowerCase())) {
                    elems[i].parentNode.parentNode.parentNode.classList.add('is-hidden');
                } else {
                    elems[i].parentNode.parentNode.parentNode.classList.remove('is-hidden');
                }
            }
        },
    }
});