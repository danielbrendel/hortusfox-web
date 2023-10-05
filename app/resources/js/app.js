/**
 * app.js
 * 
 * Put here your application specific JavaScript implementations
 */

import './../sass/app.scss';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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
        comboLocation: [],
        comboCuttingMonth: [],
        comboLightLevel: [],
        comboHealthState: [],
        confirmPhotoRemoval: 'Are you sure you want to remove this photo?',
        confirmPlantRemoval: 'Are you sure you want to remove this plant?',
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

            window.vue.bShowEditTask = true;
        },
    }
});