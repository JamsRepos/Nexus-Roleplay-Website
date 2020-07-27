function openTab(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

function dLink(linkid) {
  $.ajax({
    url: '../core/admin/dlink-form.php',
    type: 'post',
    data: {
      id: linkid,
    },
    success: function (result) {
      toastr.success('You successfully deleted link: ' + linkid, 'Success')
      $("#links").load("../core/admin/links.php");
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}

function addLink() {
  $.ajax({
    url: '../core/admin/nb-form.php',
    type: 'post',
    data: {
      title: $('#nname').val(),
      link: $('#nlink').val(),
      sort: $('#nsort').val(),
    },
    success: function (result) {
      toastr.success('You successfully added a link', 'Success')
      $("#links").load("../core/admin/links.php");
      $('input').each(function () {
        if ($(this).val() != "")
          $(this).val('');
      });
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}

function mUser(pid, uid) {
  $.ajax({
    url: '../core/admin/admin.php',
    type: 'post',
    data: {
      type: pid,
      uid: uid,
    },
    success: function (result) {
      toastr.success('You successfully updated that user', 'Success')
      $("#users").load("../core/admin/users.php");
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}

function updateIndex() {
  $.ajax({
    url: '../core/admin/form.php',
    type: 'post',
    data: {
      title: $('#name').val(),
      motto: $('#motto').val(),
    },
    success: function (result) {
      toastr.success('You successfully updated Index Settings', 'Success')
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}

function updateServer() {
  $.ajax({
    url: '../core/admin/sv_form.php',
    type: 'post',
    data: {
      ip: $('#ip').val(),
      port: $('#port').val(),
    },
    success: function (result) {
      toastr.success('You successfully updated Server Settings', 'Success')
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}