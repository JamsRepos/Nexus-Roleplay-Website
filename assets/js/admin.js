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
var parts = window.location.search.substr(1).split("&");
var $_GET = {};
for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split("=");
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
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

function oUser(uid) {
  $("#permModal").innerHTML = '';
  $("#permModal").load("../core/admin/oUsers.php?id=" + uid);
}

function getSelectedCheckboxValues(name) {
  const checkboxes = document.querySelectorAll(`input[name="${name}"]:checked`);
  let values = [];
  checkboxes.forEach((checkbox) => {
    values.push(checkbox.value);
  });
  return values;
}

function mUser(uid) {
  $.ajax({
    url: '../core/admin/admin.php',
    type: 'post',
    data: {
      uid: uid,
      perms: getSelectedCheckboxValues('perm'),
      pl: $('#pl').val(),
    },
    success: function (result) {
      toastr.success('You have successfully updated the users permissions', 'Success')
      console.log(result);
      $("#users").load("../core/admin/users.php");
      $('input').each(function () {
        if ($(this).val() != "")
          $(this).val('');
      });
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
      toastr.error(data, 'ERROR MSG')
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

function addApp() {
  $.ajax({
    url: '../core/admin/capp.php',
    type: 'post',
    data: {
      aname: $('#aname').val(),
      apic: $('#apic').val(),
      asort: $('#asort').val(),
      cd: $('#cd').val(),
    },
    beforeSend: function () {
      $('#capp_btn').attr('disabled', 'disabled');
      $('#capp_btn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Creating...');
    },
    success: function (result) {
      toastr.success('You successfully created an application', 'Success')
      $('input').each(function () {
        if ($(this).val() != "")
          $(this).val('');
      });
      $('#capp_btn').attr('disabled', false);
      $('#capp_btn').html('Save Changes');
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}

function dQst(linkid, fid) {
  $.ajax({
    url: '../core/admin/dqst-form.php',
    type: 'post',
    data: {
      id: linkid,
    },
    success: function (result) {
      toastr.success('You successfully deleted link: ' + linkid, 'Success')
      $("#qsts").load("../core/admin/questions.php?id="+fid);
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}

function updateApp(fid) {
  $.ajax({
    url: '../core/admin/ua-form.php',
    type: 'post',
    data: {
      fid: fid,
      name: $('#aname').val(),
      pic: $('#apic').val(),
      sort: $('#asort').val(),
      cd: $('#cd').val(),
    },
    beforeSend: function () {
      $('#uapp_btn').attr('disabled', 'disabled');
      $('#uapp_btn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Saving...');
    },
    success: function (result) {
      toastr.success('You successfully updated that form', 'Success')
      $('#uapp_btn').attr('disabled', false);
      $('#uapp_btn').html('Create');
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}

function addQuestion(fid) {
  $.ajax({
    url: '../core/admin/cq-form.php',
    type: 'post',
    data: {
      fid: fid,
      type: $('#atype').val(),
      qst: $('#que').val(),
      ph: $('#ph').val(),
      sb: $('#sb').val(),
      sort: $('#qsort').val(),
    },
    beforeSend: function () {
      $('#cqst_btn').attr('disabled', 'disabled');
      $('#cqst_btn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Creating...');
    },
    success: function (result) {
      toastr.success('You successfully created a question', 'Success')
      $('input').each(function () {
        if ($(this).val() != "")
          $(this).val('');
      });
      $('#cqst_btn').attr('disabled', false);
      $('#cqst_btn').html('Create');
      $("#qsts").load("../core/admin/questions.php?id="+fid);
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}

function mApp(status) {
  $.ajax({
    url: '../core/admin/ma-form.php',
    type: 'post',
    data: {
      id: $_GET['id'],
      status: status,
      reason: $('#reason').val(),
    },
    success: function (result) {
      toastr.success('You successfully updated application: ' + $_GET['id'], 'Success')
      location.reload();
    },
    error: function (data) {
      toastr.error('Woops something went wrong...', 'ERROR')
    }
  })
}