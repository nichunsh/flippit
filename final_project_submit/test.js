// var link = "seriesid=LEU0254555900&startyear=2002&endyear=2011&registrationkey=fdf58f4e4ad7464e8e86aaeaff165e55"

// ajaxPost("https://api.bls.gov/publicAPI/v2/timeseries/data/", link, function(result){
// 	console.log(result)
// 	var json = JSON.parse(result)
// 	document.querySelector('#results').innerHTML = "<pre>" + JSON.stringify(json, undefined, 4) + "</pre>"
// })


// function ajaxPost(endpointUrl, postData, returnFunction){
// 	var xhr = new XMLHttpRequest();
// 	xhr.open('POST', endpointUrl, true);
// 	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
// 	xhr.onreadystatechange = function(){
// 		if (xhr.readyState == XMLHttpRequest.DONE) {
// 			if (xhr.status == 200) {
// 				returnFunction( xhr.responseText );
// 			} else {
// 				alert('AJAX Error.');
// 				console.log(xhr.status);
// 			}
// 		}
// 	}
// 	xhr.send(postData);
// };

var query = {
	"seriesid": ["LEU0254555900", "APU0000701111"],
	"startyear": "2002",
	"endyear": "2011",
	"registrationkey": "fdf58f4e4ad7464e8e86aaeaff165e55"
}

var data = JSON.stringify(query)

ajaxPostX("https://api.bls.gov/publicAPI/v2/timeseries/data/", data, function(result){
	console.log(result)
	var json = JSON.parse(result)
	document.querySelector('#results2').innerHTML = "<pre>" + JSON.stringify(json, undefined, 4) + "</pre>"
})


function ajaxPostX(endpointUrl, postData, returnFunction){
	var length = postData.length
	var xhr = new XMLHttpRequest();
	xhr.open('POST', endpointUrl, true);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.setRequestHeader("Access-Control-Allow-Origin", "https://api.bls.gov/publicAPI/v2/timeseries/data/");
	
	xhr.onreadystatechange = function(){
		if (xhr.readyState == XMLHttpRequest.DONE) {
			if (xhr.status == 200) {
				returnFunction( xhr.responseText );
			} else {
				alert('AJAX Error.');
				console.log(xhr.status);
			}
		}
	}
	xhr.send(postData);
};