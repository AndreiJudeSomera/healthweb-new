import{j as l,p as c}from"./buttons.print-B0wxGNV-.js";import"./jquery.module-dmWpsD9K.js";import{D as i}from"./dataTables-BZwegt1j.js";import"./dataTables.buttons-DYdWO-36.js";import"./dataTables.responsive-DaNR1ydb.js";import"./modal-MwHSR4zk.js";import{v as p}from"./view-appointment-modal-DBPOqfgX.js";import{e as u,d as m}from"./delete-appointment-modal-DtpWe4Ei.js";import"./_commonjsHelpers-D6-XlEtG.js";i.Buttons.jszip(l);i.Buttons.pdfMake(c);function f(e){if(!e)return"";const t=new Date(e);if(Number.isNaN(t.getTime()))return e;let a=t.toLocaleString("en-US",{month:"long"});a=a.charAt(0).toUpperCase()+a.slice(1).toLowerCase();const n=String(t.getDate()).padStart(2,"0"),s=t.getFullYear();return`${a} ${n}, ${s}`}function b(e){if(!e)return"";const t=new Date(`1970-01-01T${e}`);return Number.isNaN(t.getTime())?e:t.toLocaleTimeString("en-US",{hour:"2-digit",minute:"2-digit",hour12:!0})}const r=new i("#patientAppointmentsTable",{retrieve:!0,ajax:{url:"/appointments",dataSrc:"",headers:{Accept:"application/json"}},initComplete:function(){this.api().column(5).search("pending").draw()},layout:{topStart:null,topEnd:null,bottomStart:"pageLength",bottom2Start:"info",bottomEnd:"paging"},responsive:!0,searching:!0,lengthChange:!0,paging:!0,order:[[2,"desc"]],columnDefs:[{targets:-1,className:"flex justify-center"}],columns:[{data:null,title:"#",orderable:!1,searchable:!1,className:"text-center bg-gray-100 rounded-tl-md",render:(e,t,a,n)=>`
    <div class="mx-1 my-1">${n.row+n.settings._iDisplayStart+1}</div>
  `},{data:"for_patient",title:'<div class="ml-3">Patient</div>',render:e=>`<div class="ml-3">${e}</div>`},{data:"appointment_date",title:"Appointment Date",render:e=>f(e)},{data:"appointment_time",title:"Time",render:e=>b(e)},{data:"appointment_type",title:"Type",render:e=>e.charAt(0).toUpperCase()+e.slice(1)},{data:"status",title:"Status",render:(e,t)=>{const a={pending:"bg-amber-100 text-amber-800",approved:"bg-emerald-100 text-emerald-800",completed:"bg-blue-100 text-blue-800",cancelled:"bg-red-100 text-red-800"};if(t==="filter"||t==="search")return(e==null?void 0:e.toUpperCase())??"";const n=e.charAt(0).toUpperCase()+e.slice(1);return`<span class="px-2 py-1 rounded-md text-xs font-medium ${a[e]||"bg-gray-100 text-gray-800"}">
              ${n}
            </span>`}},{data:"doctor",title:"Attended By",render:e=>{var a;return(a=e==null?void 0:e.user)!=null&&a.username?`Dr. ${e.user.username.split(" ")[0]}`:"Unassigned"}},{data:null,title:"Actions",orderable:!1,searchable:!1,className:"rounded-tr-md",render:(e,t,a)=>`
          <div class="w-full flex flex-row justify-start items-center gap-2">
            <button type="button"
              class="dt-action flex items-center justify-center border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8"
              data-modal-open="view-record"
              data-action="view"
              data-pid="${a.id??""}"
              >
              <i class="fa-solid fa-eye fa-sm"></i>
            </button>

            <button type="button"
              class="dt-action flex items-center justify-center border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8"
              data-modal-open="edit-record"
              data-action="edit"
              data-pid="${a.id??""}">
              <i class="fa-solid fa-pencil fa-sm"></i>
            </button>

            <button type="button"
              class="dt-action flex items-center justify-center border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md size-8"
              data-modal-open="delete-record"
              data-action="delete"
              title="Cancel appointment"
              data-pid="${a.id??""}">
              <i class="fa-solid fa-ban fa-sm"></i>
            </button>
          </div>
        `}],drawCallback:function(){this.api().column(0,{search:"applied",order:"applied"}).nodes().each((e,t)=>{e.innerHTML=`
        <div class="flex items-center justify-end h-full mx-1 my-1">
          ${t+1}
        </div>
      `})},rowCallback:function(e,t,a){$(e).addClass("border-b border-blue-200 last:border-b-0")}}),g=document.getElementById("patientAppointmentsTable");g&&document.querySelector("#patientAppointmentsTable tbody").addEventListener("click",e=>{const t=e.target.closest(".dt-action");if(!t)return;const a=t.dataset.action,n=t.dataset.pid;a==="view"?p(n):a==="edit"?u(n):a==="delete"&&m(n)});const d=document.getElementById("patientSearch");d&&d.addEventListener("input",e=>{r.search(e.target.value).draw()});const o=document.getElementById("record_add_form");o&&o.addEventListener("submit",e=>{toastr.success("New record successfully added","Success")});window.refreshAppointmentsTable=()=>r.ajax.reload(null,!1);window.filterAppointmentsByStatus=e=>{r.column(5).search(e?`^${e}$`:"",!0,!1).draw()};window.filterAppointmentsByType=e=>{r.column(4).search(e?`^${e}$`:"",!0,!1).draw()};toastr.options={closeButton:!0,debug:!0,newestOnTop:!0,progressBar:!0,positionClass:"toast-bottom-right",preventDuplicates:!0,onclick:null,showDuration:"300",hideDuration:"1000",timeOut:"5000",extendedTimeOut:"1000",showEasing:"swing",hideEasing:"linear",showMethod:"fadeIn",hideMethod:"fadeOut"};
