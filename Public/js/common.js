/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//弹出警告
function _alert(title, content){
    html = '<div style="" id="dialog2" class="weui_dialog_alert">\
                <div class="weui_mask" style="z-index: 8"></div>\
                <div class="weui_dialog" style="z-index: 9">\
                    <div class="weui_dialog_hd"><strong class="weui_dialog_title">'+title+'</strong></div>\
                    <div class="weui_dialog_bd">'+content+'</div>\
                    <div class="weui_dialog_ft">\
                        <a class="weui_btn_dialog primary" href="javascript:;">确定</a>\
                    </div>\
                </div>\
            </div>';
    $("body").append(html);
}

$(document).on("click", "#dialog2 .weui_btn_dialog", function(){
   $('#dialog2').remove();
});


