function recordtype(type, number){
    if(type != 'new' && type != 'edit'){
        return;
    } else {
        $(`#ar_error`)[0].style.display = 'none';
        if(type === 'edit'){
            $(`#func_div`)[0].style.display = 'none';
        }
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
            'activityrecord_title',
            'hands',
            'eandd',
            'iaag'
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
            $(`#${item}`)[0].value = '';
            $(`#td_${item}`)[0].style.background = '';
        });
        innerArray.forEach(function(item){
            $(`#${item}`)[0].value = '';
            if(item != 'activityrecord_title'){
                $(`#td_${item}`)[0].style.background = '';
            }
        });
        srcArray.forEach(function(item){
            $(`#${item}`)[0].src = '';
        });
        displayArray.forEach(function(item){
            $(`#${item}`)[0].style.display = 'none';
        });
        signArray.forEach(function(item){
            $(`#${item}`)[0].value = '';
            $(`#${item}`)[0].style.display = 'none';
        });
        imgsrcArray.forEach(function(item){
            $(`#${item}`)[0].src = '';
            $(`#${item}`)[0].style.display = 'none';
        });
        $('#ar_sign_div')[0].style.display = 'none';
        $('#coach_signed_btn')[0].style.display = 'block';
        const contentdiv = $('#activityrecord_content_div')[0];
        contentdiv.style.display = 'none';
        const errorTxt = $('#type_error')[0];
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
                        text['return'].forEach(function(item){
                            if(inputArray.includes(item[0])){
                                $(`#${item[0]}`)[0].value = item[1];
                            } else if(innerArray.includes(item[0])){
                                $(`#${item[0]}`)[0].value = item[1];
                            } else if(srcArray.includes(item[0])){
                                if(item[0] == 'ar_form_script'){
                                    let oldScript = $(`#${item[0]}`)[0];
                                    let newScript = document.createElement('script');
                                    newScript.src = item[1];
                                    newScript.id = 'ar_form_script';
                                    oldScript.parentNode.replaceChild(newScript, oldScript);
                                } else {
                                    $(`#${item[0]}`)[0].src = item[1];
                                }
                            } else if(displayArray.includes(item[0])){
                                $(`#${item[0]}`)[0].style.display = item[1];
                            } else if(signArray.includes(item[0])){
                                $(`#${item[0]}`)[0].value = item[1];
                                $(`#${item[0]}`)[0].style.display = 'block';
                                if(item[0] == 'coachsigndate'){
                                    $('#coach_signed_btn')[0].style.display = 'none';
                                }
                            } else if(imgsrcArray.includes(item[0])){
                                $(`#${item[0]}`)[0].src = item[1];
                                $(`#${item[0]}`)[0].style.display = 'block';
                            } else if(item[0] == 'impact_required'){
                                $('#recapimpact')[0].required = true;
                            }
                        })
                        if(type === 'edit'){
                            const mtoday = $('#mathtoday')[0];
                            const mnext = $('#mathnext')[0];
                            const mtitle = $('#math_title')[0];
                            const funcDiv = $('#func_div')[0];
                            const etoday = $('#engtoday')[0];
                            const enext = $('#engnext')[0];
                            const etitle = $('#eng_title')[0];
                            if(mtoday.innerText != '' || mnext.innerText != ''){
                                mtitle.innerHTML = 'Maths';
                                funcDiv.style.display = '';
                                mtoday.disabled = false;
                                mnext.disabled = false;
                                mtoday.style.display = 'block';
                                mnext.style.display = 'block';
                            } else {
                                mtitle.innerHTML = '';
                                mtoday.style.display = 'none';
                                mnext.style.display = 'none';
                            }
                            if(etoday.innerText != '' || enext.innerText != ''){
                                etitle.innerHTML = 'English';
                                funcDiv.style.display = '';
                                etoday.disabled = false;
                                enext.disabled = false;
                                etoday.style.display = 'block';
                                enext.style.display = 'block';
                            } else {
                                etitle.innerHTML = '';
                                etoday.style.display = 'none';
                                enext.style.display = 'none';
                            }
                        }
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