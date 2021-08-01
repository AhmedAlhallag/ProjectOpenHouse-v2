

$(document).ready(() => {
  $('#form').on('submit', async (e) => {
    e.preventDefault();
    let form = document.getElementById('form');
    // awaiting a promise is like accessing it's then -> extracting it's content
    sendDataToPage(form)
      .then((data) => {
        // console.log(data.data);
        // console.log('hhjh');
        // $(document.body).html(data.data.body  )
        document.open("text/html", "replace");
        document.write(data.data);
        document.close();
      })
  });


  const sendDataToPage = async (form) => {
    let formdata = new FormData(form)
    let api = axios.create({ basicurl: 'http://52.7.174.33:80' })
    return api.post("/explore.php", formdata)
  }
})
