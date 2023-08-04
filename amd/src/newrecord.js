$('#activityrecord_form')[0].action = 'javascript:newrecord()';
function newrecord(){
    const idsArray = [
        'apprentice',
        'reviewdate',
        'standard',
        'eors',
        'coach',
        'morm',
        'coursep',
        'courseep',
        'coursecomment',
        'otjhc',
        'otjhe',
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
        'nextdate',
        'remotef2f',
        'hands',
        'eandd',
        'iaag'
    ];
    const errorTxt = $('#ar_error')[0];
    errorTxt.style.display = 'none';
    let formData = new FormData();
    formData.append('file', $('#file')[0].files[0]);
    $('#td_file')[0].style.background = '';
    idsArray.forEach(function(arr){
        formData.append(arr, $(`#${arr}`)[0].value.replaceAll('&','($)'));
        $(`#td_${arr}`)[0].style.background = '';
    });
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/newrecord.inc.php', true);
    xhr.onload = function(){
        if(this.status == 200){
            const text = JSON.parse(this.responseText);
            if(text['error']){
                errorTxt.innerText = 'Invalid values: ';
                text['error'].forEach(function(item){
                    if(idsArray.includes(item[0])){
                        $(`#td_${item[0]}`)[0].style.background = 'red';
                        errorTxt.innerText += item[1] + '|';
                    } else if(item[0] == 'file'){
                        $(`#td_${item[0]}`)[0].style.background = 'red';
                        errorTxt.innerText += item[1] + '|';
                    }
                });
                errorTxt.style.display = 'block';
            } else {
                if(text['return']){
                    window.location.reload();
                } else {
                    errorTxt.innerText = 'Creation Error.'
                    errorTxt.style.display = 'block';
                }
            }
        } else {
            errorTxt.innerText = 'Connection Error.';
            errorTxt.style.display = 'block';
        }
    }
    xhr.send(formData);
};
$(document).ready(function(){
    $(`#func_div`)[0].style.display = '';
    const tmpArray = [['math', 'Maths'],['eng', 'English']];
    tmpArray.forEach(function(arr){
        const titleId = $(`#${arr[0]}_title`)[0];
        titleId.innerHTML = '';
        const p = document.createElement('p');
        p.className = 'c-pointer';
        p.id = `${arr[0]}_p`;
        p.innerText = `${arr[1]} required?`;
        titleId.appendChild(p);
        $(`#${arr[0]}_p`).attr('onclick', `checkbox("${arr[0]}");check_clicked("${arr[0]}");`);
        const inputAttr = document.createElement('input');
        inputAttr.type = 'checkbox';
        inputAttr.className = 'c-pointer';
        inputAttr.id = `${arr[0]}check`;
        inputAttr.style.display = 'block';
        titleId.appendChild(inputAttr);
        $(`#${arr[0]}check`).attr('onclick', `check_clicked("${arr[0]}")`);
    });
    const funcArr = ['mathtoday', 'mathnext', 'engtoday', 'engnext'];
    funcArr.forEach(function(arr){
        $(`#${arr}`).prop('disabled', true);
    });
})
function checkbox(type){
    $(`#${type}check`)[0].checked = ($(`#${type}check`)[0].checked) ? false : true;
}
function check_clicked(type){
    if(type === 'math' || type === 'eng'){
        if($(`#${type}check`).length === 1){
            const today = $(`#${type}today`)[0];
            const next = $(`#${type}next`)[0];
            let disabledVal;
            let requiredVal;
            let displayVal;
            if($(`#${type}check`)[0].checked){
                $(`#func_title0`)[0].style.display = '';
                $(`#func_title1`)[0].style.display = '';
                disabledVal = false;
                requiredVal = true;
                displayVal = 'block';
            } else {
                disabledVal = true;
                requiredVal = false;
                displayVal = 'none';
            }
            today.style.display = displayVal;
            next.style.display = displayVal;
            today.disabled = disabledVal;
            next.disabled = disabledVal;
            today.required = requiredVal;
            next.required = requiredVal;
            if($(`#mathtoday`)[0].style.display === 'none' && $(`#engtoday`)[0].style.display === 'none'){
                $(`#func_title0`)[0].style.display = 'none';
                $(`#func_title1`)[0].style.display = 'none';
            }
        }
    }
}