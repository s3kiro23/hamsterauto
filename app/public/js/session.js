$(function () {
	loadEventTimer();
	sweetToast();
});

function loadEventTimer() {
	$(window).off("click");
	$(window).on("click", sessionExtending);
}

let sessionTimer = setTimeout(function () {
	sessionEndingSoon();
}, 30000);

function sessionExtending() {
	clearTimeout(sessionTimer);
	sessionExtend();
}

function sessionEndingSoon() {
	$(window).off("click");
	$.ajax({
		url: "/src/Controller/Index/LoginController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "session_ending_soon",
		},
		success: function (response) {
			if (response["status"] === 1) {
				sweetAlertSession(response["msg"]);
			}
		},
		error: function () {
			console.log("errorSession102");
		},
	});
}

function sessionExtend() {
	$.ajax({
		url: "/src/Controller/Index/LoginController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "session_extend",
		},
		success: function (response) {
			if (response["status"] == 1) {
				sessionTimer = setTimeout(function () {
					sessionEndingSoon();
				}, response["time"] * 1000);
				loadEventTimer();
			} else {
				sessionEnding();
			}
		},
		error: function () {
			console.log("sessionError101");
		},
	});
}

function sessionEnding() {
	$.ajax({
		url: "/src/Controller/Index/LoginController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "session_ending",
		},
		success: function (response) {
			toastMixin.fire({
				animation: true,
				icon: "error",
				title: response["msg"],
			});
			if (response["status"] === "technicien") {
				setTimeout(() => {
					location.assign("/acces-prive");
				}, 2000);
			} else {
				setTimeout(() => {
					location.assign("/");
				}, 2000);
			}
		},
		error: function () {
			console.log("sessionError101");
		},
	});
}

function logOut() {
	swal
		.fire({
			confirmButtonColor: "#62C462",
			confirmButtonText: "Oui",
            cancelButtonColor: "#7F7F7F",
		    cancelButtonText: "Non",
            showCancelButton: true,
			icon: "question",
			html: "Voulez-vous vraiment vous déconnecter ?",
		})
		.then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "/src/Controller/Index/LoginController.php",
					dataType: "JSON",
					type: "POST",
					data: {
						request: "logout",
					},
					success: function (response) {
						if (response["status"] === "technicien") {
							location.assign("/acces-prive");
						} else {
							location.assign("/");
						}
					},
					error: function () {
						console.log("sessionError101");
					},
				});
			}
		});
}
