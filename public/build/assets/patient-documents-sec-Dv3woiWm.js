import"./jquery.module-dmWpsD9K.js";import{D as c}from"./dataTables-BZwegt1j.js";import"./dataTables.buttons-DYdWO-36.js";import"./dataTables.responsive-DaNR1ydb.js";async function u(a){const t=await fetch(`/consultations/${encodeURIComponent(a)}`,{headers:{Accept:"application/json"},credentials:"same-origin"});if(!t.ok)throw new Error("Fetch failed");return t.json()}async function m(a){try{const t=await u(a),i=document.getElementById("edit-document-sec");if(!i){console.warn("edit-document-sec modal not found in DOM");return}i.dispatchEvent(new CustomEvent("edit-ready",{detail:t,bubbles:!1}))}catch(t){toastr==null||toastr.error("Failed to load document data."),console.error(t)}}const f={consultation:{label:"Consultation",cls:"bg-blue-100 text-blue-800"},"medical-certificate":{label:"Medical Certificate",cls:"bg-emerald-100 text-emerald-800"},"referral-letter":{label:"Referral Letter",cls:"bg-amber-100 text-amber-800"},prescription:{label:"Prescription",cls:"bg-purple-100 text-purple-800"}};function p(a){const t=f[a]??{label:a??"-",cls:"bg-gray-100 text-gray-700"};return`<span class="px-2 py-0.5 rounded text-xs font-semibold ${t.cls}">${t.label}</span>`}function b(a){if(!a)return"—";const t=new Date(a);return Number.isNaN(t.getTime())?a:t.toLocaleDateString("en-US",{month:"long",day:"numeric",year:"numeric"})}document.addEventListener("DOMContentLoaded",function(){const a=window.location.pathname.split("/"),t=a[a.length-1],i=new c("#patientDocumentsTablesSecView",{retrieve:!0,ajax:{url:`/consultations/patient/${t}`,dataSrc:"",headers:{Accept:"application/json"}},initComplete:function(){this.api().column(1).search("Consultation").draw()},layout:{topStart:null,topEnd:null,bottomStart:"pageLength",bottom2Start:"info",bottomEnd:"paging"},responsive:!0,searching:!0,lengthChange:!0,filtering:!0,paging:!0,fixedColumns:!0,columnDefs:[{targets:-1,className:"flex justify-end",width:10},{targets:0,width:10,className:"text-center flex justify-end w-10 "}],columns:[{data:null,title:"#",orderable:!1,searchable:!1,className:"text-center bg-gray-100 rounded-tl-md",render:(e,n,o,r)=>`
      <div>${r.row+r.settings._iDisplayStart+1}</div>
    `},{data:"document_type",title:'<div class="ml-5">Record Type</div>',render:e=>`<div class="ml-5">${p(e)}</div>`},{data:"created_at",title:"Date",render:e=>b(e)},{data:null,title:"Actions",orderable:!1,searchable:!1,className:"rounded-tr-md flex justify-left",render:(e,n,o)=>{const r=o.document_type??"consultation";let l=`
      <a type="button"
        class="dt-action px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8 flex justify-center items-center"
        href="/consultations/${o.id??""}/${r}"
        target="_blank"
        rel="noopener noreferrer"
        title="View PDF">
        <i class="fa-solid fa-eye fa-sm"></i>
      </a>
    `;return r==="consultation"&&(l+=`
        <button type="button"
          class="dt-action px-2 py-1 border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8 flex justify-center items-center"
          data-modal-open="edit-document-sec"
          data-action="edit"
          data-id="${o.id??""}"
          title="Edit">
          <i class="fa-solid fa-pen fa-sm"></i>
        </button>
      `),`<div class="w-full flex flex-row justify-left items-center gap-2">${l}</div>`}}],drawCallback:function(){this.api().column(0,{search:"applied",order:"applied"}).nodes().each((e,n)=>{e.innerHTML=`
        <div class="flex items-center justify-end h-full mx-1 my-1 ">
          ${n+1}
        </div>
      `})},rowCallback:function(e,n,o){$(e).addClass("border-b border-blue-200 last:border-b-0")}});document.querySelector("#patientDocumentsTablesSecView tbody").addEventListener("click",async e=>{var l,d;const n=e.target.closest(".dt-action");if(!n)return;const o=n.dataset.action,r=n.dataset.id;o==="edit"&&(m(r),(l=window.Modal)==null||l.open("edit-document-sec")),o==="add-document"&&(await createConsultationDocumentModalInit(r),(d=window.Modal)==null||d.open("create-consultation-document"))});const s=document.getElementById("searchRecords");s&&(s.addEventListener("input",e=>{i.search(e.target.value).draw()}),s.addEventListener("change",e=>{i.search(e.target.value).draw()})),window.refreshDocumentsTable=()=>i.ajax.reload(null,!1),window.filterDocumentsTable=e=>{i.column(1).search(e?`^${e}$`:"",!0,!1).draw()}});
