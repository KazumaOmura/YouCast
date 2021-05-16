window.addEventListener('DOMContentLoaded', function(){

	// 「name=gender」を持つ全てのラジオボタンを取得
	var input_genders = document.querySelectorAll("input[name=TAB-A]");

	for(var element of input_genders) {

		element.addEventListener('change',function(){
			if( this.checked ) {
                // p要素を取得
                var p1_element = document.getElementById("content_div");

                // クラス名を追加
                if (this.value == 1){
                    document.getElementById("content_div1").classList.remove("none");
                    document.getElementById("content_div2").classList.add("none");
                    document.getElementById("content_div1").classList.add("content");
                    document.getElementById("content_div2").classList.remove("content");

                    document.getElementById("header_switch_para1").classList.add("switch_blue");
                    document.getElementById("header_switch_para2").classList.remove("switch_blue");
                }
                else if (this.value == 2){
                    document.getElementById("content_div2").classList.remove("none");
                    document.getElementById("content_div1").classList.add("none");
                    document.getElementById("content_div2").classList.add("content");
                    document.getElementById("content_div1").classList.remove("content");

                    document.getElementById("header_switch_para2").classList.add("switch_blue");
                    document.getElementById("header_switch_para1").classList.remove("switch_blue");
                }
			}
		});
	}

});

function setRequired( $required ) {
    var $elementReference = document.getElementById( "invitation_code" );
    $elementReference.required = $required;
    var $required = $elementReference.required;
}

    window.addEventListener('DOMContentLoaded', function(){
        var header_Height = document.getElementById("header").clientHeight
        console.log(header_Height);
        document.getElementById("header_bottom_div").style.height = header_Height  + "px";
    });