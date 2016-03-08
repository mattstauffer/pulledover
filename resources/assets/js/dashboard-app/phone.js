"use strict";

module.exports = {
    template:'#phone-template',
    props:['phone'],
    data:function(){
        return {
            iconClass:{
                // todo use font-awesome mixin to make number--[un]verified an icon
                'fa-check-square-o': this.phone.is_verified,
                'number--verified': this.phone.is_verified,
                'fa-square-o': !this.phone.is_verified,
                'number--unverified': !this.phone.is_verified
            }
        }
    },
    filters:{
        phone:function(number){
            return '('+number.substring(0,3)+') '+number.substring(3,6)+'-'+number.substring(6)
        }
    }
};