function course_clicked(id){
    const errorTxt = $('#activityrecord_error')[0];
    errorTxt.style.display = 'none';
    const params = `id=${id}`;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/teacher_course.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText);
            if(text['error']){
                errorTxt.innerText = text['error'];
                errorTxt.style.display = 'block';
            } else {
                if(text['return']){
                    $('#course_content_div')[0].innerHTML = text['return'];
                }
            }
        }
    }
    xhr.send(params);    
}