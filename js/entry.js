function CheckWordRUS(word){
	for(var i = 0; i < word.length; i++)
	{
		if(word[i] >= 'а' && word[i] <='я'){
			return false;
		}
	}
	return true;
}


// Валидация
function check_entry()
{
	var Form = document.entry_data;
	var valueLogin = Form.login.value;
	var valuePassword = Form.password.value;
	if(CheckWordRUS(valueLogin) && CheckWordRUS(valuePassword)){
		Form.submit();
	}
	else{
		alert("Русские буквы недопустимы!");
		Form.reset();
	}

}

function check_registr()
{
	var Form = document.registr_data;
	var valueLogin = Form.login.value;
	var valuePassword = Form.password.value;
	var valueName = Form.name.value;
	if(CheckWordRUS(valueLogin) && CheckWordRUS(valuePassword)){
		Form.submit();
	}
	else{
		alert("Русские буквы недопустимы в логине и пароле!");
		Form.reset();
	}
}


function ch_c1()
{
	var b = document.entry_data.but;
	b.style.backgroundColor = "orange";
}

function ch_c2()
{
	var b = document.entry_data.but;
	b.style.backgroundColor = "white";
}


