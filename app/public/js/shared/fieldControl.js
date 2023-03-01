$(function () {
	$("#inputImmatNew").on("keyup", checkNewValueRegEx);
	$("#inputImmatOld").on("keyup", checkOldValueRegEx);
	$("#inputYear").on("keyup", checkOldValueYear);
});

let oldvalue = "";

/*REGEX IMMAT DEBUT*/
function checkNew(s) {
	let toks = s.split("-");
	switch (toks.length) {
		case 3:
			if (!/^[A-Za-z]{0,2}$/.test(toks[2].trim())) return false;
		case 2:
			if (!/^\d{0,3}$/.test(toks[1].trim())) return false;
		case 1:
			return /^[A-Za-z]{0,2}$/.test(toks[0].trim());
		default:
			return false;
	}
}

let checkNewValueRegEx = function () {
	if (!checkNew(this.value)) {
		this.value = oldvalue;
	} else {
		oldvalue = this.value = this.value.toUpperCase();
	}
};

function checkOld(s) {
	let toks = s.split("-");
	switch (toks.length) {
		case 3:
			if (!/^\d?[A-Za-z0-9]{0,2}$/.test(toks[2].trim())) return false;
		/*if (!/^\d{0,3}$/.test(toks[2].trim())) return false;*/
		case 2:
			if (!/^[A-Za-z]{0,3}$/.test(toks[1].trim())) return false;
		case 1:
			return /^\d{0,4}$/.test(toks[0].trim());
		default:
			return false;
	}
}

let checkOldValueRegEx = function () {
	if (!checkOld(this.value)) {
		this.value = oldvalue;
	} else {
		oldvalue = this.value = this.value.toUpperCase();
	}
};

/*REGEX IMMAT FIN*/

function checkYear(s) {
	let toks = s;
	switch (toks.length) {
		case 4:
			if (!/^\d{0,4}$/.test(toks[3].trim())) return false;
		case 3:
			if (toks[0].trim() == 1) {
				if (!/^[5-9]{0,4}$/.test(toks[2].trim())) return false;
			} else {
				if (!/^[0]{0,2}$/.test(toks[1].trim())) return false;
			}
		case 2:
			if (toks[0].trim() == 1) {
				if (!/^[9]{0,2}$/.test(toks[1].trim())) return false;
			} else {
				if (!/^[0]{0,2}$/.test(toks[1].trim())) return false;
			}
		case 1:
			return /^[1-2]{0,2}$/.test(toks[0].trim());
		default:
			return false;
	}
}

let checkOldValueYear = function () {
	if (!checkYear(this.value)) {
		this.value = oldvalue;
	} else {
		oldvalue = this.value;
	}
};

let checkField = function () {
	let focusedField = this.id;
	let tabFields = {};
	tabFields[focusedField] = $("#" + focusedField).val();

	$.ajax({
		url: "/src/Controller/CheckFieldController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "checkField",
			data: JSON.stringify(tabFields),
		},
		success: function (response) {
			let inputField = $("#" + focusedField);
			let labelField = $("label[for='" + focusedField + "']");
			console.log(inputField);
			if (response["status"] === 1) {
				if (inputField.hasClass("is-invalid")) {
					inputField.removeClass("is-invalid");
					inputField.attr("id") === "inputTel"
						? inputField.parent().next().html("")
						: "";
				}
				if (
					inputField.attr("id") !== "inputImmatNew" &&
					inputField.attr("id") !== "inputImmatOld" &&
					inputField.attr("id") !== "inputPasswdLogin" &&
					inputField.attr("id") !== "inputLogin"
				) {
					inputField.addClass("is-valid");
					labelField.addClass("form-label-valid");
					labelField.removeClass("form-label-invalid");
					labelField.removeClass("form-label-contact-invalid");
				}
			} else {
				if (
					inputField.attr("id") !== "inputImmatNew" &&
					inputField.attr("id") !== "inputImmatOld" &&
					inputField.attr("id") !== "inputTel" &&
					inputField.attr("id") !== "inputLogin" &&
					inputField.attr("id") !== "inputPasswdLogin"
				) {
					inputField.removeClass("is-valid");
					labelField.removeClass("form-label-valid");
					if (inputField.attr("id") === "inputTexte") {
						labelField.addClass("form-label-contact-invalid");
					} else {
						labelField.addClass("form-label-invalid");
					}
					inputField.addClass("is-invalid");
					labelField.next(".invalid-feedback").html(response["msg"]);
					if (labelField.next().is("span")) {
						labelField.next().next(".invalid-feedback").html(response["msg"]);
					}
				} else {
					if (
						inputField.attr("id") !== "inputLogin"
					) {
						inputField.removeClass("is-valid");
						inputField.addClass("is-invalid");
						inputField.parent().next().html(response["msg"]);
					}
				}
			}
		},
		error: function () {},
	});
};

let placeholderAnimation = function () {
	let focusedField = this.id;
	let inputField = $("#" + focusedField);
	let formattedString = formatString(focusedField);
	let labelField = $("label[for='" + focusedField + "']");
	if (
		inputField.attr("placeholder") == " " ||
		inputField.attr("placeholder") == null
	) {
		inputField.attr("placeholder", "Entrez votre " + formattedString + " ici");
	} else if (
		inputField.attr("placeholder") ==
		"Entrez votre " + formattedString + " ici"
	) {
		inputField.attr("placeholder", " ");
	}
};

function formatString(str) {
	// Chercher la position du mot "input"
	var inputIndex = str.indexOf("input");

	// Si le mot "input" est trouvé, supprimer le mot et mettre le suivant en minuscule
	if (inputIndex !== -1) {
		// Trouver la position de la fin du mot suivant
		var endIndex = str.indexOf(" ", inputIndex);
		if (endIndex === -1) {
			endIndex = str.length;
		}
		// Extraire le mot suivant et le mettre en minuscule
		var nextWord = str.substring(inputIndex + 5, endIndex).toLowerCase();
		if (nextWord == "year") {
			nextWord = "année";
		} else if (nextWord == "old-password") {
			nextWord = "ancien mot de passe";
		} else if (nextWord == "password") {
			nextWord = "nouveau mot de passe";
		} else if (nextWord == "password2") {
			nextWord = "confirmation";
		} else if (nextWord == "passwdlogin") {
			nextWord = "mot de passe";
		} else if (nextWord == "login") {
			nextWord = "email";
		}
		// Concaténer les parties de la chaîne avec le mot suivant en minuscule
		str = str.substring(0, inputIndex) + nextWord + str.substring(endIndex);
	}

	return str;
}

//---------fonction show password--------------------------//
function showPassword() {
	if ($(".inputPassword").prop("type") == "password") {
		$(".inputPassword").prop("type", "text");
		$(".eyeShow").removeClass("fa-eye").addClass("fa-eye-slash");
	} else if ($(".inputPassword").prop("type") == "text") {
		$(".inputPassword").prop("type", "password");
		$(".eyeShow").removeClass("fa-eye-slash").addClass("fa-eye");
	}
}
//--------------------------------------------------------//
