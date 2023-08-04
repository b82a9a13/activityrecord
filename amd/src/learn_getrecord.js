function getrecord(number){
    $(`#func_div`)[0].style.display = 'none';
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
        'hands',
        'eandd',
        'iaag'
    ];
    const signArray = [
        'learnsigndate',
        'coachsigndate'
    ];
    const srcArray = [
        'filesrc',
        'coachsignimg',
        'learnsignimg'
    ]
    inputArray.forEach(function(item){
        $(`#${item}`)[0].value = '';
    });
    innerArray.forEach(function(item){
        $(`#${item}`)[0].innerText = '';
    });
    srcArray.forEach(function(item){
        $(`#${item}`)[0].src = '';
        $(`#${item}`)[0].style.display = 'none';
    })
    signArray.forEach(function(item){
        $(`#${item}`)[0].value = '';
        $(`#${item}`)[0].style.display = 'none';
    })
    $('#learn_signed_btn')[0].style.display = 'block';
    const contentdiv = $('#activityrecord_content_div')[0];
    contentdiv.style.display = 'none';
    const errorTxt = $('#edit_error')[0];
    errorTxt.style.display = 'none';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/learn_getrecord.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(this.status == 200){
            const text = JSON.parse(this.responseText);
            if(text['error']){
                errorTxt.innerText = text['error'];
                errorTxt.style.display = 'block';
            } else {
                if(text['return']){
                    text['return'].forEach(function(item){
                        if(inputArray.includes(item[0])){
                            $(`#${item[0]}`)[0].value = item[1];
                        } else if(innerArray.includes(item[0])){
                            $(`#${item[0]}`)[0].innerText = item[1];
                        } else if(item[0] == 'learnsignimg'){
                            $(`#${item[0]}`)[0].src = item[1];
                            $(`#${item[0]}`)[0].style.display = 'block';
                            $('#learn_signed_btn')[0].style.display = 'none';
                        } else if(srcArray.includes(item[0])){
                            $(`#${item[0]}`)[0].src = item[1];
                            $(`#${item[0]}`)[0].style.display = 'block';
                        } else if(signArray.includes(item[0])){
                            $(`#${item[0]}`)[0].value = item[1];
                            $(`#${item[0]}`)[0].style.display = 'block';
                        }
                    });
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
                        etoday.style.display = 'block';
                        enext.style.display = 'block';
                    } else {
                        etitle.innerHTML = '';
                        etoday.style.display = 'none';
                        enext.style.display = 'none';
                    }
                    contentdiv.style.display = 'block';
                }   
            }
        } else {
            errorTxt.innerText = 'Connection error.';
            errorTxt.style.display = 'block';
        }
    }
    xhr.send(`num=${number}`);
}