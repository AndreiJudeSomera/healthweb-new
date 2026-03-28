import{j as p,p as m}from"./buttons.print-B0wxGNV-.js";import"./jquery.module-dmWpsD9K.js";import{D as r}from"./dataTables-BZwegt1j.js";import"./dataTables.buttons-DYdWO-36.js";import"./dataTables.responsive-DaNR1ydb.js";import"./modal-MwHSR4zk.js";import{v as f}from"./view-appointment-modal-DBPOqfgX.js";import{e as b,d as g}from"./delete-appointment-modal-DtpWe4Ei.js";import"./_commonjsHelpers-D6-XlEtG.js";r.Buttons.jszip(p);r.Buttons.pdfMake(m);function y(e){if(!e)return"";const t=new Date(e);return Number.isNaN(t.getTime())?e:t.toLocaleDateString("en-GB",{day:"2-digit",month:"long",year:"numeric"})}function h(e){if(!e)return"";const t=new Date(`1970-01-01T${e}`);return Number.isNaN(t.getTime())?e:t.toLocaleTimeString("en-US",{hour:"2-digit",minute:"2-digit",hour12:!0})}async function v(e){if(navigator.clipboard&&navigator.clipboard.writeText)return await navigator.clipboard.writeText(e),!0;const t=document.createElement("textarea");t.value=e,t.setAttribute("readonly",""),t.style.position="fixed",t.style.left="-9999px",document.body.appendChild(t),t.select(),t.setSelectionRange(0,t.value.length);const a=document.execCommand("copy");return document.body.removeChild(t),a}const n=document.getElementById("copyPidButtonx");var l;const o=((l=document.getElementById("viewPid"))==null?void 0:l.getHTML())??"";async function d(e){if(o&&n.dataset.busy!=="1"){n.dataset.busy="1",n.disabled=!0;try{const t=await v(o);toastr.clear(),t?toastr.success(o,"Copied to clipboard"):toastr.error(o,"Failed to copy PID")}finally{n.dataset.busy="0",n.disabled=!1}}}n&&(n.removeEventListener("click",d),n.addEventListener("click",d));const u=new r("#patientRecordsTable",{retrieve:!0,ajax:{url:`/appointments/patient/${o}`,dataSrc:"",headers:{Accept:"application/json"}},layout:{topStart:null,topEnd:null,bottomStart:"pageLength",bottom2Start:"info",bottomEnd:"paging"},responsive:!0,searching:!0,lengthChange:!0,paging:!0,columnDefs:[{targets:-1,className:"flex justify-center"}],columns:[{data:"appointment_date",title:"Appointment Date",render:e=>y(e)},{data:"appointment_time",title:"Time",render:e=>h(e)},{data:"appointment_type",title:"Type",render:e=>e.toUpperCase()},{data:"status",title:"Status",render:e=>e.toUpperCase()},{data:"doctor",title:"Attended By",render:e=>{var t;return(t=e==null?void 0:e.user)!=null&&t.username?`Dr. ${e.user.username}`:"Unassigned"}},{data:null,title:"Actions",orderable:!1,searchable:!1,render:(e,t,a)=>`
          <div class="w-full flex flex-row justify-center items-center gap-2">
            <button type="button"
              class="dt-action px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8"
              data-modal-open="view-record"
              data-action="view"
              data-pid="${a.id??""}"
              >
              <i class="fa-solid fa-eye fa-sm"></i>
            </button>

            <button type="button"
              class="dt-action px-2 py-1 border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8"
              data-modal-open="edit-record"
              data-action="edit"
              data-pid="${a.id??""}">
              <i class="fa-solid fa-pencil fa-sm"></i>
            </button>

            <button type="button"
              class="dt-action px-2 py-1 border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md size-8"
              data-modal-open="delete-record"
              data-action="delete"
              data-pid="${a.id??""}">
              <i class="fa-solid fa-trash fa-sm"></i>
            </button>
          </div>
        `}]}),T=document.getElementById("patientRecordsTable");T&&document.querySelector("#patientRecordsTable tbody").addEventListener("click",e=>{const t=e.target.closest(".dt-action");if(!t)return;const a=t.dataset.action,i=t.dataset.pid;a==="view"?f(i):a==="edit"?b(i):a==="delete"&&g(i)});const s=document.getElementById("patientSearch");s&&s.addEventListener("input",e=>{u.search(e.target.value).draw()});const c=document.getElementById("record_add_form");c&&c.addEventListener("submit",e=>{toastr.success("New record successfully added","Success")});window.refreshAppointmentsTable=()=>u.ajax.reload(null,!1);toastr.options={closeButton:!0,debug:!0,newestOnTop:!0,progressBar:!0,positionClass:"toast-bottom-right",preventDuplicates:!0,onclick:null,showDuration:"300",hideDuration:"1000",timeOut:"5000",extendedTimeOut:"1000",showEasing:"swing",hideEasing:"linear",showMethod:"fadeIn",hideMethod:"fadeOut"};
