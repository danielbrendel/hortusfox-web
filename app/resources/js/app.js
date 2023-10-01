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
        bShowUploadPhoto: false,
        comboLocation: [],
        comboCuttingMonth: [],
        comboLightLevel: [],
        comboHealthState: [],
        confirmPhotoRemoval: 'Are you sure you want to remove this photo?',
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

        showEditText: function(plant, property)
        {
            document.getElementById('inpEditTextPlantId').value = plant;
            document.getElementById('inpEditTextAttribute').value = property;
            window.vue.bShowEditText = true;
        },

        showEditBoolean: function(plant, property, hint)
        {
            document.getElementById('inpEditBooleanPlantId').value = plant;
            document.getElementById('inpEditBooleanAttribute').value = property;
            document.getElementById('property-hint').innerHTML = hint;
            window.vue.bShowEditBoolean = true;
        },

        showEditInteger: function(plant, property)
        {
            document.getElementById('inpEditIntegerPlantId').value = plant;
            document.getElementById('inpEditIntegerAttribute').value = property;
            window.vue.bShowEditInteger = true;
        },

        showEditDate: function(plant, property)
        {
            document.getElementById('inpEditDatePlantId').value = plant;
            document.getElementById('inpEditDateAttribute').value = property;
            window.vue.bShowEditDate = true;
        },

        showEditCombo: function(plant, property, combo)
        {
            document.getElementById('inpEditComboPlantId').value = plant;
            document.getElementById('inpEditComboAttribute').value = property;
            
            if (typeof combo !== 'object') {
                console.error('Invalid combo specified');
                return;
            }

            let sel = document.getElementById('selEditCombo');
            if (sel) {
                combo.forEach(function(elem, index){
                    let opt = document.createElement('option');
                    opt.value = elem.ident;
                    opt.text = elem.label;
                    sel.add(opt);
                });
            }

            window.vue.bShowEditCombo = true;
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
    }
});