$(function () {
	generateNavbar();
	displayCar();
	let currentPageH =
		$("#pagesHold").find(".active").children().html() == undefined
			? 1
			: $("#pagesHold").find(".active").children().html();
	let currentPageP =
		$("#pagesInProgress").find(".active").children().html() == undefined
			? 1
			: $("#pagesInProgress").find(".active").children().html();
	let currentPageO =
		$("#pagesOver").find(".active").children().html() == undefined
			? 1
			: $("#pagesOver").find(".active").children().html();
	setInterval(() => {
		loadAwaiting(currentPageH);
		loadInProgress(currentPageP);
		loadArchives(currentPageO);
	}, 3000);
});

let loadEvent = function () {
	$("#searchImmat").off("keyup");
	$("#searchImmat").on("keyup", loadVehiculeEnAttente);
};

function displayCar() {
	$.ajax({
		url: "/src/Controller/DisplayHTML/TablesTechDisplayController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "autorisation",
		},
		success: function (response) {
			if (response["status"] === 1) {
				displayBackOffice();
			} else {
				toastMixin.fire({
					animation: true,
					title: response["msg"],
					icon: "error",
				});
				setTimeout(() => {
					window.location.replace("/");
				}, 1500);
			}
		},
		error: function () {},
	});
}

function displayBackOffice() {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/BackofficeController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_backoffice",
		},
		success: function (response) {
			$("#backOfficeBody").html(response["html"]);
			displayFiltreImmat();
			loadInProgress(1);
			loadArchives(1);
		},
		error: function () {},
	});
}

function displayFiltreImmat() {
	$.ajax({
		url: "/src/Controller/DisplayHTML/TablesTechDisplayController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "display_registration",
		},
		success: function (response) {
			$("#filtreImmat").html(response);
			generateDateBO();
		},
		error: function () {},
	});
}
let loadAwaiting = function (page) {
	$.ajax({
		url: "/src/Controller/DisplayHTML/TablesTechDisplayController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "loadInterventionRecap",
			page: page,
			type: "awaiting",
			registration: $("#searchImmat").val(),
			currentDate: $(".currentDate ").attr("id"),
		},
		success: function (response) {
            console.log(response["count"]);
			if (response["count"] === 0) {
				$("#vehiculeAttente").html(response["htmlAwaiting"]);
				$("#pagesHold").html("");
			} else {
				$("#vehiculeAttente").html(response["htmlAwaiting"]);
				$("#pagesHold").html(response["paginationAwaiting"]);
				$("#pageH" + page).addClass("active");
			}
			if (response["user"] == "admin") {
				dataTableAdmin();
			}
		},
		error: function () {},
	});
};

let loadInProgress = function (page) {
	$.ajax({
		url: "/src/Controller/DisplayHTML/TablesTechDisplayController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "loadInterventionRecap",
			page: page,
			type: "inprogress",
		},
		success: function (response) {
			$("#interventionTab").html(response["htmlInProgress"]);
			$("#pagesInProgress").html(response["paginationInProgress"]);
			$("#pageP" + page).addClass("active");
		},
		error: function () {},
	});
};

let loadArchives = function (page) {
	$.ajax({
		url: "/src/Controller/DisplayHTML/TablesTechDisplayController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "loadInterventionRecap",
			page: page,
			type: "archives",
			currentDate: $(".currentDate ").attr("id"),
		},
		success: function (response) {
			$("#vehiculesTermines").html(response["htmlTechHistory"]);
			$("#pagesOver").html(response["paginationTechHistory"]);
			$("#pageA" + page).addClass("active");
		},
		error: function () {},
	});
};

let loadVehiculeEnAttente = function () {
	loadAwaiting(1);
};
// Gestion des tableaux Backoffice DEBUT

function switchDayRdv(switchDate) {
	$("#searchImmat").val("");
	let page = 1;
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/BackofficeController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "switch_day_rdv",
			page: page,
			timestamp: switchDate,
			registration: "",
		},
		success: function (response) {
			if (response["html"]) {
				$("#vehiculeAttente").html(response["html"]);
				$("#pagesHold").html(response["paginationHoldNext"]);
				$("#pageH" + page).addClass("active");
				generateDateBO(response["time"]);
			}
		},
	});
}

// Gestion des tableaux Backoffice FIN

// Gestion des dates DEBUT
function generateDateBO(timestampID = 0) {
	$.ajax({
		url: "/src/Controller/DashboardBackoffice/BackofficeController.php",
		dataType: "JSON",
		type: "POST",
		data: {
			request: "generate_date_BO",
			currentDate: timestampID,
		},
		success: function (response) {
			$("#dateDuJour").html(response["html_day"]["currentDay"]);
			$(".btnBack").html(response["html_day"]["btnBack"]);
			$(".btnNext").html(response["html_day"]["btnNext"]);
			$(".btnPrevious").html(response["html_day"]["btnPrevious"]);
			loadVehiculeEnAttente();
			loadEvent();
		},
		error: function () {
			console.log("errordayCases");
		},
	});
}

// Gestion des dates FIN
