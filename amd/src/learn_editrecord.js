$('#activityrecord_content_div')[0].addEventListener('submit', (e)=>{
    e.preventDefault();
    const date = $('#learnsigndate')[0].value;
    const com = $('#apprencom')[0].value;
    const errorTxt = $('#ar_error')[0];
    errorTxt.style.display = 'none';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/learn_editrecord.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(this.status == 200){
            const text = JSON.parse(this.responseText);
            console.log(text);
            if(text['error']){
                errorTxt.innerText = 'Invalid values: ';
                text['error'].forEach(function(item){
                    if(item[0] == 'learnsigndate' || item[0] == 'apprencom'){
                        $(`#td_${item[0]}`)[0].style.background = 'red';
                        errorTxt.innerText += item[1] + '|';
                    }
                })
                errorTxt.style.display = 'block';
            } else {
                if(text['return']){
                    window.location.reload();
                } else {
                    errorTxt.innerText = 'Creation error.';
                    errorTxt.style.display = 'block';
                }
            }
        } else {
            errorTxt.innerText = 'Connection error.';
            errorTxt.style.display = 'block';
        }
    }
    xhr.send(`date=${date}&com=${com}`);
})