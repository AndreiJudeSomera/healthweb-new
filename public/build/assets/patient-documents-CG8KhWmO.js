import"./jquery.module-dmWpsD9K.js";import{D as c}from"./dataTables-BZwegt1j.js";import"./dataTables.buttons-DYdWO-36.js";import"./dataTables.responsive-DaNR1ydb.js";import{d as u,e as m}from"./edit-document-modal-CfnHuJPW.js";import"./delete-document-modal-hQsxBwc5.js";import"./buttons.print-B0wxGNV-.js";import"./_commonjsHelpers-D6-XlEtG.js";import"./modal-MwHSR4zk.js";import"./view-appointment-modal-DBPOqfgX.js";import"./delete-appointment-modal-DtpWe4Ei.js";async function f(a){const t=await fetch(`/consultations/${encodeURIComponent(a)}`,{headers:{Accept:"application/json"},credentials:"same-origin"});if(!t.ok)throw new Error("Failed to load consultation");return t.json()}async function p(a){try{const t=await f(a),i=document.getElementById("create-consultation-document");if(!i){console.warn("create-consultation-document modal not found in DOM");return}i.dispatchEvent(new CustomEvent("consultation-ready",{detail:t,bubbles:!1}))}catch(t){toastr==null||toastr.error("Failed to load consultation data."),console.error(t)}}const b={consultation:{label:"Consultation",cls:"bg-blue-100 text-blue-800"},"medical-certificate":{label:"Medical Certificate",cls:"bg-emerald-100 text-emerald-800"},"referral-letter":{label:"Referral Letter",cls:"bg-amber-100 text-amber-800"},prescription:{label:"Prescription",cls:"bg-purple-100 text-purple-800"}};function g(a){const t=b[a]??{label:a??"-",cls:"bg-gray-100 text-gray-700"};return`<span class="px-2 py-0.5 rounded text-xs font-semibold ${t.cls}">${t.label}</span>`}function y(a){if(!a)return"—";const t=new Date(a);return Number.isNaN(t.getTime())?a:t.toLocaleDateString("en-US",{month:"long",day:"numeric",year:"numeric"})}document.addEventListener("DOMContentLoaded",function(){const a=window.location.pathname.split("/"),t=a[a.length-1],i=new c("#patientDocumentsTable",{retrieve:!0,ajax:{url:`/consultations/patient/${t}`,dataSrc:"",headers:{Accept:"application/json"}},initComplete:function(){this.api().column(1).search("Consultation").draw()},layout:{topStart:null,topEnd:null,bottomStart:"pageLength",bottom2Start:"info",bottomEnd:"paging"},responsive:!0,searching:!0,lengthChange:!0,filtering:!0,paging:!0,fixedColumns:!0,columnDefs:[{targets:-1,className:"flex justify-end",width:10},{targets:0,width:10,className:"text-center flex justify-end w-10 "}],columns:[{data:null,title:"#",orderable:!1,searchable:!1,className:"text-center bg-gray-100 rounded-tl-md",render:(e,r,n,o)=>`
  <div>${o.row+o.settings._iDisplayStart+1}</div>
`},{data:"document_type",title:'<div class="ml-5">Record Type</div>',render:e=>`<div class="ml-5">${g(e)}</div>`},{data:"created_at",title:"Date",render:e=>y(e)},{data:null,title:"Actions",orderable:!1,searchable:!1,className:"rounded-tr-md flex justify-left",render:(e,r,n)=>{const o=n.document_type??"consultation",d=o==="consultation"?`<button type="button"
                class="dt-action px-2 py-1 border-2 border-emerald-700 text-emerald-700 hover:bg-emerald-100 rounded-md size-8 flex justify-center items-center"
                data-action="add-document"
                data-id="${n.id??""}"
                title="Generate Document">
                <i class="fa-solid fa-file-medical fa-sm"></i>
              </button>`:"";return`
            <div class="w-full flex flex-row justify-left items-center gap-2">
              <a type="button"
                class="dt-action px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8 flex justify-center items-center"
                href="/consultations/${n.id??""}/${o}"
                target="_blank"
                rel="noopener noreferrer"
                title="View PDF">
                <i class="fa-solid fa-eye fa-sm"></i>
              </a>
              <button type="button"
                class="dt-action px-2 py-1 border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8 flex justify-center items-center"
                data-modal-open="edit-document-p"
                data-action="edit"
                data-id="${n.id??""}"
                title="Edit">
                <i class="fa-solid fa-pen fa-sm"></i>
              </button>
              <button type="button"
                class="dt-action px-2 py-1 border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md size-8 flex justify-center items-center"
                data-modal-open="delete-document"
                data-action="delete"
                data-id="${n.id??""}"
                title="Delete">
                <i class="fa-solid fa-trash fa-sm"></i>
              </button>
              ${d}
            </div>
          `}}],drawCallback:function(){this.api().column(0,{search:"applied",order:"applied"}).nodes().each((e,r)=>{e.innerHTML=`
        <div class="flex items-center justify-end h-full mx-1 my-1 ">
          ${r+1}
        </div>
      `})},rowCallback:function(e,r,n){$(e).addClass("border-b border-blue-200 last:border-b-0")}});document.querySelector("#patientDocumentsTable tbody").addEventListener("click",async e=>{var d,s;const r=e.target.closest(".dt-action");if(!r)return;const n=r.dataset.action,o=r.dataset.id;n==="delete"&&u(o),n==="edit"&&(m(o),(d=window.Modal)==null||d.open("edit-document-p")),n==="add-document"&&(await p(o),(s=window.Modal)==null||s.open("create-consultation-document"))});const l=document.getElementById("searchRecords");l&&(l.addEventListener("input",e=>{i.search(e.target.value).draw()}),l.addEventListener("change",e=>{i.search(e.target.value).draw()})),window.refreshDocumentsTable=()=>i.ajax.reload(null,!1),window.filterDocumentsTable=e=>{i.column(1).search(e?`^${e}$`:"",!0,!1).draw()}});
