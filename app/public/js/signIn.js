$(function () {
	captcha();
	$("#to-cgu").on("click", modalCGU);
	$("#to-mentions").on("click", modalMentions);
	$("#to_logIn").on("click", to_logIn);
	$(".form-control").on("change", checkField);
	$(".form-control").on("focusin", placeholderAnimation);
	$(".form-control").on("focusout", placeholderAnimation);
	$("#inputTel").intlTelInput({
		preferredCountries: ["fr", "gb"],
		utilsScript: "/vendor/jackocnr/intl-tel-input/build/js/utils.js",
		initialCountry: "fr",
		geoIpLookup: function (success, failure) {
			$.get("https://ipinfo.io", function () {}, "jsonp").always(function (
				resp
			) {
				const countryCode = resp && resp.country ? resp.country : "fr";
				success(countryCode);
			});
		},
	});
});

let signIn = function () {
	let tabInput = {};
	tabInput[$("input[name=civilite]:checked").attr("name")] = $(
		"input[name=civilite]:checked"
	).val();
	tabInput[$("#captcha").attr("id")] = $("#captcha").html();
	$(".field").each(function () {
		tabInput[$(this).attr("id")] = $(this).val();
	});
	$.ajax({
		url: "/src/Controller/Index/SignInController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "signIn",
			tabInput: JSON.stringify(tabInput),
		},
		success: function (response) {
			if (response["status"] === 0) {
				Swal.fire({
					title: "Erreur",
					text: response["msg"],
					icon: "warning",
					showCancelButton: true,
					showConfirmButton: false,
					cancelButtonColor: "#3085d6",
					cancelButtonText: "Retry!",
				});
			} else {
				$("#sign-in").prop("disabled", true);
				Swal.fire({
					title: response["msg"]["title"],
					text: response["msg"]["text"],
					icon: "success",
					showCancelButton: true,
					confirmButtonColor: "#62C462",
					confirmButtonText: "OK",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showCancelButton: false,
				}).then((result) => {
					if (result.isConfirmed) {
						window.location.replace("/");
					}
				});
			}
		},

		error: function () {},
	});
};

function captcha() {
	$.ajax({
		url: "/src/Controller/Index/SignInController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "captcha",
		},
		success: function (response) {
			$("#captcha").html(response["get_captcha"]);
		},
		error: function () {},
	});
}

let to_logIn = function () {
	Swal.fire({
		title: "Redirection vers la page de connexion",
		imageUrl: "../public/assets/img/swalicons/spinner.gif",
		imageWidth: 220,
		imageHeight: 220,
		allowEscapeKey: false,
		allowOutsideClick: false,
		showCancelButton: false,
		showConfirmButton: false,
		timer: 2000,
	});
	setTimeout(() => {
		window.location.replace("/");
	}, 2000);
};
