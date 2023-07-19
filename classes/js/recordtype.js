function recordtype(type, number){
    if(type != 'new' && type != 'edit'){
        return;
    } else {
        const inputArray = [
            'apprentice',
            'reviewdate',
            'standard',
            'eors',
            'coach',
            'morm',
            'coursep',
            'courseep',
            'otjhc',
            'otjhe',
            'nextdate',
            'remotef2f'
        ];
        const innerArray = [
            'coursecomment',
            'otjhcomment',
            'recap',
            'recapimpact',
            'details',
            'detailsmod',
            'impact',
            'mathtoday',
            'mathnext',
            'engtoday',
            'engnext',
            'aln',
            'coachfeed',
            'safeguard',
            'agreedact',
            'apprencom',
            'activityrecord_title'
        ];
        const srcArray = [
            'ar_form_script',
            'filesrc'
        ];
        const displayArray = [
            'ar_sign_div'
        ];
        const signArray = [
            'learnsigndate',
            'coachsigndate'
        ];
        const imgsrcArray = [
            'coachsignimg',
            'learnsignimg'
        ];
        inputArray.forEach(function(item){
            document.getElementById(item).value = '';
        });
        innerArray.forEach(function(item){
            document.getElementById(item).innerText = '';
        });
        srcArray.forEach(function(item){
            document.getElementById(item).src = '';
        });
        displayArray.forEach(function(item){
            document.getElementById(item).style.display = 'none';
        });
        signArray.forEach(function(item){
            document.getElementById(item).value = '';
            document.getElementById(item).style.display = 'none';
        });
        imgsrcArray.forEach(function(item){
            document.getElementById(item).src = '';
            document.getElementById(item).style.display = 'none';
        });
        document.getElementById('ar_sign_div').style.display = 'none';
        document.getElementById('coach_signed_btn').style.display = 'block';
        const contentdiv = document.getElementById('activityrecord_content_div');
        contentdiv.style.display = 'none';
        const errorTxt = document.getElementById('type_error');
        errorTxt.style.display = 'none';
        const params = `type=${type}&num=${number}`;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', './classes/inc/recordtype.inc.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function(){
            if(this.status == 200){
                let text = JSON.parse(this.responseText);
                if(text['error']){
                    errorTxt.innerText = text['error'];
                    errorTxt.style.display = 'block';
                } else{
                    if(text['return']){
                        console.log(text['return']);
                        text['return'].forEach(function(item){
                            if(inputArray.includes(item[0])){
                                document.getElementById(item[0]).value = item[1];
                            } else if(innerArray.includes(item[0])){
                                document.getElementById(item[0]).innerHTML = item[1];
                            } else if(srcArray.includes(item[0])){
                                if(item[0] == 'ar_form_script'){
                                    let oldScript = document.getElementById(item[0]);
                                    let newScript = document.createElement('script');
                                    newScript.src = item[1];
                                    newScript.id = 'ar_form_script';
                                    oldScript.parentNode.replaceChild(newScript, oldScript);
                                } else {
                                    document.getElementById(item[0]).src = item[1];
                                }
                            } else if(displayArray.includes(item[0])){
                                document.getElementById(item[0]).style.display = item[1];
                            } else if(signArray.includes(item[0])){
                                document.getElementById(item[0]).value = item[1];
                                document.getElementById(item[0]).style.display = 'block';
                                if(item[0] == 'coachsigndate'){
                                    document.getElementById('coach_signed_btn').style.display = 'none';
                                }
                            } else if(imgsrcArray.includes(item[0])){
                                document.getElementById(item[0]).src = item[1];
                                document.getElementById(item[0]).style.display = 'block';
                            } else if(item[0] == 'impact_required'){
                                document.getElementById('recapimpact').required = true;
                            }
                        })
                        contentdiv.style.display = 'block';
                    }
                }
            } else {
                errorTxt.innerText = 'Connection error.'
                errorTxt.style.display = 'block';
            }
        }
        xhr.send(params);
    }
}