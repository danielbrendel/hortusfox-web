/**
 * app.js
 * 
 * Put here your application specific JavaScript implementations
 */

import './../sass/app.scss';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Chart from 'chart.js/auto';
import 'chartjs-adapter-date-fns';

window.constChatMessageQueryRefreshRate = 1000 * 15;
window.constChatUserListRefreshRate = 1000 * 15;
window.constChatTypingRefreshRate = 2000;

window.vue = new Vue({
    el: '#app',

    data: {
        bShowAddPlant: false,
        bShowEditText: false,
        bShowEditMultilineText: false,
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
        bShowPreviewImageModal: false,
        bShowSharePhoto: false,
        bShowAddFirstLocation: false,
        bShowAddCalendarItem: false,
        bShowEditCalendarItem: false,
        bShowCreateNewCalendarClass: false,
        bShowPlantQRCode: false,
        bShowPlantBulkPrint: false,
        bShowAddCustomPlantAttribute: false,
        bShowEditCustomPlantAttribute: false,
        clsLastImagePreviewAspect: '',
        comboLocation: [],
        comboCuttingMonth: [],
        comboLightLevel: [],
        comboHealthState: [],
        confirmPhotoRemoval: 'Are you sure you want to remove this photo?',
        confirmPlantRemoval: 'Are you sure you want to remove this plant?',
        confirmSetAllWatered: 'Are you sure you want to update the last watered date of all these plants?',
        confirmSetAllRepotted: 'Are you sure you want to update the last repotted date of all these plants?',
        confirmSetAllFertilised: 'Are you sure you want to update the last fertilised date of all these plants?',
        confirmInventoryItemRemoval: 'Are you sure you want to remove this item?',
        confirmPlantAddHistory: 'Please confirm if you want to do this action.',
        confirmPlantRemoveHistory: 'Please confirm if you want to do this action.',
        newChatMessage: 'New',
        currentlyOnline: 'Currently online: ',
        loadingPleaseWait: 'Please wait...',
        noListItemsSelected: 'No items selected',
        editProperty: 'Edit property',
        copiedToClipboard: 'Content has been copied to clipboard.',
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

        showEditMultilineText: function(plant, property, defval, anchor = '')
        {
            document.getElementById('inpEditMultilineTextPlantId').value = plant;
            document.getElementById('inpEditMultilineTextAttribute').value = property;
            document.getElementById('inpEditMultilineTextValue').value = defval;
            document.getElementById('inpEditMultilineTextAnchor').value = anchor;
            window.vue.bShowEditMultilineText = true;
        },

        showEditBoolean: function(plant, property, hint, defval)
        {
            document.getElementById('inpEditBooleanPlantId').value = plant;
            document.getElementById('inpEditBooleanAttribute').value = property;
            document.getElementById('property-hint').innerHTML = hint;

            if (defval) {
                document.getElementById('inpEditBooleanValue_yes').checked = true;
            } else {
                document.getElementById('inpEditBooleanValue_no').checked = true;
            }
            
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

        selectDataTypeInputField: function(elem, field) {
            field.classList.remove('is-hidden');

            if (!field.children[1].children[0].classList.contains('is-hidden')) {
                field.children[1].children[0].classList.add('is-hidden');
            }

            if (!field.children[1].children[1].classList.contains('is-hidden')) {
                field.children[1].children[1].classList.add('is-hidden');
            }

            if (!field.children[1].children[2].classList.contains('is-hidden')) {
                field.children[1].children[2].classList.add('is-hidden');
            }

            field.children[1].children[0].children[0].children[1].children[0].children[0].disabled = true;
            field.children[1].children[0].children[0].children[2].children[0].children[0].disabled = true;
            field.children[1].children[1].disabled = true;
            field.children[1].children[2].disabled = true;

            if (elem.value === 'bool') {
                field.children[1].children[0].classList.remove('is-hidden');
                field.children[1].children[0].children[0].children[1].children[0].children[0].disabled = false;
                field.children[1].children[0].children[0].children[2].children[0].children[0].disabled = false;
            } else if (elem.value === 'datetime') {
                field.children[1].children[2].classList.remove('is-hidden');
                field.children[1].children[2].disabled = false;
            } else {
                field.children[1].children[1].classList.remove('is-hidden');
                field.children[1].children[1].disabled = false;
            }
        },

        showEditCustomPlantAttribute: function(id, plant, label, datatype, content, anchor = '')
        {
            document.getElementById('edit-plant-attribute-attr').value = id;
            document.getElementById('edit-plant-attribute-plant').value = plant;
            document.getElementById('edit-plant-attribute-label').value = label;
            document.getElementById('edit-plant-attribute-datatype').value = datatype;

            let elFieldTarget = document.getElementById('field-custom-edit-attribute-content');

            if (datatype === 'bool') {
                if (content == 1) {
                    elFieldTarget.children[1].children[0].children[0].children[1].children[0].children[0].checked = true;
                } else {
                    elFieldTarget.children[1].children[0].children[0].children[2].children[0].children[0].checked = true;
                }
            } else if (datatype === 'datetime') {
                elFieldTarget.children[1].children[2].value = content;
            } else {
                elFieldTarget.children[1].children[1].value = content;
            }

            window.vue.selectDataTypeInputField(document.querySelector('#edit-plant-attribute-datatype'), elFieldTarget);

            window.vue.bShowEditCustomPlantAttribute = true;
        },

        removeCustomPlantAttribute: function(id, target)
        {
            window.vue.ajaxRequest('post', window.location.origin + '/plants/attributes/remove?id=' + id, {}, function(response){
                if (response.code == 200) {
                    let elem = document.getElementById(target);
                    if (elem) {
                        elem.remove();
                    }
                    window.vue.bShowEditCustomPlantAttribute = false;
                } else {
                    alert(response.msg);
                }
            });
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

        deletePhoto: function(photo, plant, target)
        {
            if (!confirm(window.vue.confirmPhotoRemoval)) {
                return;
            }

            window.vue.ajaxRequest('post', window.location.origin + '/plants/details/gallery/photo/remove', { photo: photo, plant: plant }, function(response){
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

        markHistorical: function(plant) {
            if (!confirm(window.vue.confirmPlantAddHistory)) {
                return;
            }

            location.href = window.location.origin + '/plants/history/add?plant=' + plant;
        },

        unmarkHistorical: function(plant) {
            if (!confirm(window.vue.confirmPlantRemoveHistory)) {
                return;
            }

            location.href = window.location.origin + '/plants/history/remove?plant=' + plant;
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

        removeTask: function(id)
        {
            window.vue.ajaxRequest('post', window.location.origin + '/tasks/remove', { task: id }, function(response){
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

        updateLastWatered: function(id)
        {
            if (!confirm(window.vue.confirmSetAllWatered)) {
                return;
            }

            location.href = window.location.origin + '/plants/location/' + id + '/water';
        },

        updateLastRepotted: function(id)
        {
            if (!confirm(window.vue.confirmSetAllRepotted)) {
                return;
            }

            location.href = window.location.origin + '/plants/location/' + id + '/repot';
        },

        updateLastFertilised: function(id)
        {
            if (!confirm(window.vue.confirmSetAllFertilised)) {
                return;
            }

            location.href = window.location.origin + '/plants/location/' + id + '/fertilise';
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

        editInventoryItem: function(id, name, group, location, description)
        {
            document.getElementById('inpInventoryItemId').value = id;
            document.getElementById('inpInventoryItemName').value = name;
            document.getElementById('inpInventoryItemGroup').value = group;
            document.getElementById('inpInventoryItemLocation').value = location;
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

            let html = '';

            if (!elem.system) {
                html = `
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
            } else {
                html = `
                <div class="system-message">
                    <div class="system-message-left system-message-left-new">
                        <div class="system-message-context">` + elem.userName + ` @ ` + elem.created_at + `</div>
                        
                        <div class="system-message-content">` + elem.message + `</div>
                    </div>

                    <div class="system-message-right">
                        <div class="system-message-new chat-message-new">` + window.vue.newChatMessage + `</div>
                    </div>
                </div>
                `;
            }

            return html;
        },

        refreshUserList: function()
        {
            window.vue.ajaxRequest('get', window.location.origin + '/chat/user/online', {}, function(response) {
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

        fetchUnreadMessageCount: function(target) {
            window.vue.ajaxRequest('get', window.location.origin + '/chat/messages/count', {}, function(response) {
                if (response.code == 200) {
                    if (response.count > 0) {
                        target.classList.remove('is-hidden');
                        target.children[0].innerText = response.count;
                    } else {
                        target.classList.add('is-hidden');
                    }
                }
            });

            setTimeout(window.vue.fetchUnreadMessageCount.bind(null, target), window.constChatMessageQueryRefreshRate);
        },

        fetchNewSystemMessage: function(target) {
            window.vue.ajaxRequest('get', window.location.origin + '/chat/system/message/latest', {}, function(response) {
                if (response.code == 200) {
                    if (response.message) {
                        window.vue.fadeSystemMessage(target, window.vue.renderNewSystemMessage(response.message), response.message.id);
                    
                        let audio = new Audio(window.location.origin + '/snd/new_message.wav');
                        audio.onloadeddata = function() {
                            audio.play();
                        };
                    }
                }
            });

            setTimeout(window.vue.fetchNewSystemMessage.bind(null, target), window.constChatMessageQueryRefreshRate);
        },

        renderNewSystemMessage: function(elem) {
            let html = `
                <div class="system-message-small fade fade-out" id="system-message-small-` + elem.id + `">
                    <div class="system-message-small-context">` + elem.userName + ` @ ` + elem.created_at + `</div>

                    <div class="system-message-small-content">` + elem.message + `</div>
                </div>
            `;

            return html;
        },

        fadeSystemMessage: function(target, code, id) {
            target.innerHTML = code + target.innerHTML;

            let fadeElem = document.getElementById('system-message-small-' + id);

            setTimeout(function() {
                fadeElem.classList.replace('fade-out', 'fade-in');
            }, 250);
            
            setTimeout(function() {
                fadeElem.classList.replace('fade-in', 'fade-out');
            }, 5000);
        },

        textFilterElements: function(token) {
            let elems = document.getElementsByClassName('plant-card-title');
            for (let i = 0; i < elems.length; i++) {
                let target = elems[i].parentNode;
                
                while (!target.classList.contains('plant-card')) {
                    target = target.parentNode;
                }

                if (!elems[i].innerText.toLowerCase().includes(token.toLowerCase())) {
                    target.classList.add('is-hidden');
                } else {
                    target.classList.remove('is-hidden');
                }
            }
        },

        filterTasks: function(token) {
            let elems = document.getElementsByClassName('task');
            for (let i = 0; i < elems.length; i++) {
                let elemTitle = elems[i].children[1].children[0];
                let elemDescription = elems[i].children[2].children[0];

                if ((elemTitle.innerText.toLowerCase().includes(token.toLowerCase())) || (elemDescription.innerText.toLowerCase().includes(token.toLowerCase()))) {
                    elems[i].classList.remove('is-hidden'); 
                } else {
                    elems[i].classList.add('is-hidden'); 
                }
            }
        },

        filterInventory: function(token) {
            let elems = document.getElementsByClassName('inventory-item');
            for (let i = 0; i < elems.length; i++) {
                let elemName = elems[i].children[1].children[0];
                let elemDescription = elems[i].children[2].children[0];

                if ((elemName.innerText.toLowerCase().includes(token.toLowerCase())) || (elemDescription.innerText.toLowerCase().includes(token.toLowerCase()))) {
                    elems[i].classList.remove('is-hidden'); 
                } else {
                    elems[i].classList.add('is-hidden'); 
                }
            }
        },

        toggleDropdown: function(elem) {
            if (elem.classList.contains('is-active')) {
                elem.classList.remove('is-active');
            } else {
                elem.classList.add('is-active');
            }
        },

        selectAdminTab: function(tab) {
            const tabs = ['environment', 'media', 'users', 'locations', 'attributes', 'calendar', 'mail', 'themes', 'backup', 'weather', 'api', 'info'];

            let selEl = document.querySelector('.admin-' + tab);
            if (selEl) {
                tabs.forEach(function(elem, index) {
                    let otherEl = document.querySelector('.admin-' + elem);
                    if (otherEl) {
                        otherEl.classList.add('is-hidden');
                    }

                    let otherTabs = document.querySelector('.admin-tab-' + elem);
                    if (otherTabs) {
                        otherTabs.classList.remove('is-active');
                    }
                });

                selEl.classList.remove('is-hidden');

                let selTab = document.querySelector('.admin-tab-' + tab);
                if (selTab) {
                    selTab.classList.add('is-active');
                }
            }
        },

        switchAdminTab: function(tab) {
            location.href = window.location.origin + '/admin?tab=' + tab;
        },

        showImagePreview: function(asset, aspect = 'is-3by5') {
            let img = document.getElementById('preview-image-modal-img');
            if (img) {
                img.src = asset;

                if (window.vue.clsLastImagePreviewAspect.length > 0) {
                    img.parentNode.classList.remove(window.vue.clsLastImagePreviewAspect);
                }

                window.vue.clsLastImagePreviewAspect = aspect;
                img.parentNode.classList.add(window.vue.clsLastImagePreviewAspect);

                window.vue.bShowPreviewImageModal = true;
            }
        },

        showSharePhoto: function(asset, title, type) {
            document.getElementById('share-photo-title').value = title;
            document.getElementById('share-photo-id').value = asset;
            document.getElementById('share-photo-type').value = type;

            document.getElementById('share-photo-result').classList.add('is-hidden');
            document.getElementById('share-photo-error').classList.add('is-hidden');
            document.getElementById('share-photo-submit-action').classList.remove('is-hidden');

            window.vue.bShowSharePhoto = true;
        },

        performPhotoShare: function(asset, title, type, result, button, error) {
            let origButtonHtml = button.innerHTML;
            button.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>&nbsp;' + window.vue.loadingPleaseWait;

            window.vue.ajaxRequest('post', window.location.origin + '/share/photo/post', { asset: asset, title: title, type: type }, function(response) {
                button.innerHTML = origButtonHtml;

                if (response.code == 200) {
                    result.value = response.data.url;
                    result.parentNode.parentNode.classList.remove('is-hidden');
                    button.classList.add('is-hidden');
                    error.classList.add('is-hidden');
                } else {
                    error.innerHTML = response.msg;
                    error.classList.remove('is-hidden');
                }
            });
        },

        generateNewToken: function(target, button) {
            let oldTxt = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            window.vue.ajaxRequest('post', window.location.origin + '/admin/cronjob/token', {}, function(response) {
                button.innerHTML = oldTxt;

                if (response.code == 200) {
                    target.value = response.token;
                } else {
                    alert(response.msg);
                }
            });
        },

        startBackup: function(button, plants, gallery, tasks, inventory, calendar) {
            let oldText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp;' + oldText;

            window.vue.ajaxRequest('post', window.location.origin + '/export/start', {
                plants: plants,
                gallery: gallery,
                tasks: tasks,
                inventory: inventory,
                calendar: calendar
            }, function(response) {
                button.innerHTML = oldText;

                if (response.code == 200) {
                    let export_result = document.getElementById('export-result');
                    if (export_result) {
                        export_result.classList.remove('is-hidden');

                        export_result.children[1].href = response.file;
                        export_result.children[1].innerHTML = response.file;
                    }
                }
            });
        },

        startImport: function(button, file, locations, plants, gallery, tasks, inventory, calendar) {
            let oldText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp;' + oldText;
            
            let formData = new FormData();
            formData.append('import', file.files[0]);
            formData.append('locations', ((locations) ? 1 : 0));
            formData.append('plants', ((plants) ? 1 : 0));
            formData.append('gallery', ((gallery) ? 1 : 0));
            formData.append('tasks', ((tasks) ? 1 : 0));
            formData.append('inventory', ((inventory) ? 1 : 0));
            formData.append('calendar', ((calendar) ? 1 : 0));

            window.vue.ajaxRequest('post', window.location.origin + '/import/start', formData, function(response) {
                button.innerHTML = oldText;

                if (response.code == 200) {
                    let import_result = document.getElementById('import-result');
                    if (import_result) {
                        import_result.classList.remove('is-hidden');
                    }
                }
            });
        },

        startThemeImport: function(file, button) {
            let oldText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp;' + oldText;
            
            let formData = new FormData();
            formData.append('theme', file.files[0]);

            window.vue.ajaxRequest('post', window.location.origin + '/admin/themes/import', formData, function(response) {
                button.innerHTML = oldText;
                
                if (response.code == 200) {
                    let import_result = document.getElementById('themes-import-result');
                    if (import_result) {
                        import_result.innerText = import_result.innerText.replace('{count}', response.themes.length);
                        import_result.classList.remove('is-hidden');
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        removeTheme: function(theme) {
            window.vue.ajaxRequest('post', window.location.origin + '/admin/themes/remove', { theme: theme }, function(response) {
                if (response.code == 200) {
                    let tableElem = document.getElementById('admin-themes-list-item-' + theme);
                    if (tableElem) {
                        tableElem.remove();
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        renderCalendar: function(elem, date_from, date_till = null) {
            window.vue.ajaxRequest('post', window.location.origin + '/calendar/query', { date_from: date_from, date_till: date_till }, function(response){
                if (response.code == 200) {
                    let content = document.getElementById(elem);
                    if (content) {
                        let data = response.data;

                        data.sort(function (a, b) {
                            return new Date(a.date_from) - new Date(b.date_from);
                        });

                        const labels = data.map(x => {
                            return [x.name];
                        });

                        const newData = data.map(x => {
                            return [x.date_from.split(' ')[0], x.date_till.split(' ')[0], x.class_name, x.id, x.class_descriptor]
                        });
                        
                        let colorsBackground = [];
                        data.forEach(function(elem, index) {
                            colorsBackground.push(elem.color_background);
                        });
                        
                        let colorsBorder = [];
                        data.forEach(function(elem, index) {
                            colorsBorder.push(elem.color_border);
                        });

                        const config = {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        data: newData,
                                        backgroundColor: colorsBackground,
                                        borderColor: colorsBorder,
                                        borderWidth: 1,
                                        fill: false,
                                        barPercentage: 0.3
                                    }
                                ]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                scales: {
                                    x: {
                                        min: response.date_from,
                                        max: response.date_till,
                                        type: 'time',
                                        time: {
                                            unit: 'day'
                                        }
                                    },
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false,
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.dataset.data[context.dataIndex][2];
                                            },
                                            afterBody: function(context) {
                                                return context[0].raw[0] + ' - ' + context[0].raw[1];
                                            }
                                        }
                                    }
                                },

                            }
                        };

                        if (window.calendarChart !== null) {
                            window.calendarChart.destroy();
                        }
                        
                        window.calendarChart = new Chart(
                            content,
                            config
                        );

                        content.onclick = function(event) {
                            let points = window.calendarChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
                            if (points.length) {
                                const firstPoint = points[0];
                                const label = window.calendarChart.data.labels[firstPoint.index];
                                const value = window.calendarChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
                                console.log(value);
                                if (value.length) {
                                    document.getElementById('inpEditCalendarItemId').value = value[3];
                                    document.getElementById('inpEditCalendarItemName').value = label;
                                    document.getElementById('inpEditCalendarItemDateFrom').value = value[0];
                                    document.getElementById('inpEditCalendarItemDateTill').value = value[1];
                                    document.getElementById('inpEditCalendarItemClass').value = value[4];
                                    window.vue.bShowEditCalendarItem = true;
                                }
                            }
                        };
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        removeCalendarItem: function(ident) {
            window.vue.ajaxRequest('post', window.location.origin + '/calendar/remove', { ident: ident }, function(response) {
                if (response.code == 200) {
                    location.reload();
                } else {
                    alert(response.msg);
                }
            });
        },

        removeCalendarClass: function(id) {
            window.vue.ajaxRequest('post', window.location.origin + '/admin/calendar/class/remove', { id: id }, function(response) {
                if (response.code == 200) {
                    let elItem = document.getElementById('admin-calendar-class-item-' + id);
                    if (elItem) {
                        elItem.remove();
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        clonePlant: function(id) {
            window.vue.ajaxRequest('post', window.location.origin + '/plants/clone', { id: id }, function(response) {
                if (response.code == 200) {
                    location.href = window.location.origin + '/plants/details/' + response.clone_id;
                } else {
                    alert(response.msg);
                }
            });
        },

        generateAndShowQRCode: function(plant) {
            window.vue.ajaxRequest('get', window.location.origin + '/plants/qrcode?plant=' + plant, {}, function(response) {
                if (response.code == 200) {
                    let elTarget = document.getElementById('image-plant-qr-code');
                    if (elTarget) {
                        elTarget.src = response.qrcode;
                        window.vue.bShowPlantQRCode = true;
                    }
                } else {
                    alert(response.msg);
                }
            });
        },

        printQRCode: function(content) {
            let elPlantTitle = document.getElementById('title-plant-qr-code');

            let wnd = window.open('', elPlantTitle.value, 'height=auto, width=auto');

            wnd.document.write('<html><head><title>' + elPlantTitle.value + '</title></head><body>');
            wnd.document.write('<img src="' + content + '"/>');
            wnd.document.write('</body></html>');

            wnd.print();
            wnd.close();
        },

        bulkChecked: function(target, flag) {
            let elems = document.getElementsByClassName(target);
            
            if (elems) {
                Array.prototype.forEach.call(elems, function(elem){ 
                    elem.checked = flag; 
                });
            }
        },

        bulkPrintQRCodes: function(target, location) {
            let plantIds = [];

            let elems = document.getElementsByClassName(target);
            if (elems) {
                Array.prototype.forEach.call(elems, function(elem) {
                    if (elem.checked) {
                        plantIds.push([elem.dataset.plantid, elem.dataset.plantname]);
                    }
                });

                if (plantIds.length >= 0) {
                    window.vue.ajaxRequest('post', window.location.origin + '/plants/qrcode/bulk', { list: JSON.stringify(plantIds) }, function(response) {
                        if (response.code == 200) {
                            let wnd = window.open('', location, 'height=auto, width=auto');

                            wnd.document.write('<html><head><title>' + location + '</title></head><body>');

                            response.list.forEach(function(elem, index) {
                                wnd.document.write('<div style="position: relative; display: inline-block; margin-left: 10px; margin-right: 10px; margin-bottom: 10px;">#' + elem.plantid + ' ' + elem.plantname + '<br/><img src="' + elem.qrcode + '" width="152" height="152"/></div>');
                            });

                            wnd.document.write('</body></html>');

                            wnd.print();
                            wnd.close();
                        } else {
                            alert(response.msg);
                        }
                    });
                } else {
                   alert(window.vue.noListItemsSelected); 
                }
            }
        },

        editGalleryPhotoLabel: function(id, plant, old) {
            let newLabel = prompt(window.vue.editProperty, old);
            if (newLabel.length) {
                window.vue.ajaxRequest('post', window.location.origin + '/plants/details/gallery/photo/label/edit', { id: id, label: newLabel, plant: plant }, function(response) {
                    if (response.code == 200) {
                        document.getElementById('photo-gallery-item-' + id).children[0].children[0].innerHTML = newLabel;
                    } else {
                        alert(response.msg);
                    }
                });
            }
        },

        acquireGeoPosition: function(destLatitude, destLongitude, button) {
            let oldText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>&nbsp;' + button.innerHTML;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    destLatitude.value = position.coords.latitude;
                    destLongitude.value = position.coords.longitude;

                    button.innerHTML = oldText;
                });
            } else {
                button.innerHTML = oldText;
                
                alert('Geolocation is not available');
            }
        },

        toggleApiKey: function(id) {
            window.vue.ajaxRequest('get', window.location.origin + '/admin/api/' + id + '/toggle', {}, function(response) {
                if (response.code == 200) {
                    document.getElementById('api-key-checkbox-' + id).checked = response.active;
                } else {
                    alert(response.msg);
                }
            });
        },

        toggleAdminPlantAttribute: function(name) {
            window.vue.ajaxRequest('get', window.location.origin + '/admin/attribute/update?name=' + name, {}, function(response) {
                if (response.code == 200) {
                    document.getElementById('admin-attributes-checkbox-' + name).checked = response.active;
                } else {
                    alert(response.msg);
                }
            });
        },

        copyToClipboard: function(text) {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert(window.vue.copiedToClipboard);
        },
    }
});