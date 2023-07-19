function delrecord(id){
    const modaldiv = document.getElementById(`modal_${id}`);
    if(modaldiv.style.display == 'none'){
        modaldiv.style.display = 'block';
    } else if(modaldiv.style.display == 'block'){
        const modalError = document.getElementById(`modal_${id}_error`);
        modalError.innerText = '';
        modalError.style.display = 'none';
        const xhr = new XMLHttpRequest();
        xhr.open('POST', './classes/inc/delrecord.inc.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function(){
            if(this.status == 200){
                const text = JSON.parse(this.responseText);
                if(text['error']){
                    modalError.innerText = text['error'];
                    modalError.style.display = 'block';
                } else {
                    if(text['return']){
                        window.location.reload();
                    } else {
                        modalError.innerText = 'Deletion error.';
                        modalError.style.display = 'block';
                    }
                }
            } else {
                modalError.innerText = 'Connection error.';
                modalError.style.display = 'block';
            }
        }
        xhr.send(`id=${id}`);
    }
}
function closedelrecord(id){
    document.getElementById(`modal_${id}`).style.display = 'none';
}