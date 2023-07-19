function getrecord(number){
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
        'apprencom'
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
        document.getElementById(item).value = '';
    });
    innerArray.forEach(function(item){
        document.getElementById(item).innerText = '';
    });
    srcArray.forEach(function(item){
        document.getElementById(item).src = '';
        document.getElementById(item).style.display = 'none';
    })
    signArray.forEach(function(item){
        document.getElementById(item).value = '';
        document.getElementById(item).style.display = 'none';
    })
    document.getElementById('learn_signed_btn').style.display = 'block';
    const contentdiv = document.getElementById('activityrecord_content_div');
    contentdiv.style.display = 'none';
    const errorTxt = document.getElementById('edit_error');
    errorTxt.style.display = 'none';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/learn_getrecord.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(this.status == 200){
            const text = JSON.parse(this.responseText);
            console.log(text);
            if(text['error']){
                errorTxt.innerText = text['error'];
                errorTxt.style.display = 'block';
            } else {
                if(text['return']){
                    text['return'].forEach(function(item){
                        if(inputArray.includes(item[0])){
                            document.getElementById(item[0]).value = item[1];
                        } else if(innerArray.includes(item[0])){
                            document.getElementById(item[0]).innerText = item[1];
                        } else if(item[0] == 'learnsignimg'){
                            document.getElementById(item[0]).src = item[1];
                            document.getElementById(item[0]).style.display = 'block';
                            document.getElementById('learn_signed_btn').style.display = 'none';
                        } else if(srcArray.includes(item[0])){
                            document.getElementById(item[0]).src = item[1];
                            document.getElementById(item[0]).style.display = 'block';
                        } else if(signArray.includes(item[0])){
                            document.getElementById(item[0]).value = item[1];
                            document.getElementById(item[0]).style.display = 'block';
                        }
                    });
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