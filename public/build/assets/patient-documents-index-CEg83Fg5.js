import"./jquery.module-dmWpsD9K.js";import{D as T}from"./dataTables-BZwegt1j.js";import{f as v}from"./delete-document-modal-hQsxBwc5.js";import{d as x,e as I}from"./edit-document-modal-CfnHuJPW.js";import"./buttons.print-B0wxGNV-.js";import"./_commonjsHelpers-D6-XlEtG.js";import"./dataTables.buttons-DYdWO-36.js";import"./dataTables.responsive-DaNR1ydb.js";import"./modal-MwHSR4zk.js";import"./view-appointment-modal-DBPOqfgX.js";import"./delete-appointment-modal-DtpWe4Ei.js";function D(){const r=document.getElementById("create_document_form"),a=r==null?void 0:r.querySelector('button[type="submit"]');document.getElementById("patient_pid");const d=document.getElementById("document_type");document.getElementById("wt"),document.getElementById("bp"),document.getElementById("cr"),document.getElementById("rr"),document.getElementById("temperature"),document.getElementById("spo2"),document.getElementById("history_physical_exam"),document.getElementById("diagnosis");const e=document.getElementById("referral_to"),o=document.getElementById("referral_reason"),n=document.getElementById("prescription_meds");document.getElementById("remarks"),d.addEventListener("change",()=>{d.value!=="referral-letter"?(e.disabled=!0,o.disabled=!0,e.classList.add("opacity-25"),o.classList.add("opacity-25")):(e.disabled=!1,o.disabled=!1,e.classList.remove("opacity-25"),o.classList.remove("opacity-25")),d.value!=="prescription"?(n.disabled=!0,n.classList.add("opacity-25")):(n.disabled=!1,n.classList.remove("opacity-25"))}),r.addEventListener("submit",async s=>{var l,m,u,p,f;s.preventDefault();const i=new FormData(r);a&&(a.disabled=!0,a.dataset.originalText=a.innerHTML,a.innerHTML="Saving...");const g=()=>{var t;return((t=document.querySelector('meta[name="csrf-token"]'))==null?void 0:t.content)??""},h=t=>{var c;if(!t)return"Request failed";if(t.message)return t.message;if(t.errors&&typeof t.errors=="object"){const E=Object.keys(t.errors)[0],b=(c=t.errors[E])==null?void 0:c[0];if(b)return b}return"Request failed"};try{const t=await fetch("/consultations",{method:"POST",headers:{Accept:"application/json","X-CSRF-TOKEN":g()},credentials:"same-origin",body:i}),c=await t.json().catch(()=>null);if(!t.ok){(l=toastr==null?void 0:toastr.error)==null||l.call(toastr,h(c));return}(m=toastr==null?void 0:toastr.success)==null||m.call(toastr,"Document created!"),r.reset(),(u=document.querySelector('[data-modal-close="create-document"]'))==null||u.click(),(p=window.refreshDocumentsTable)==null||p.call(window)}catch(t){console.error(t),(f=toastr==null?void 0:toastr.error)==null||f.call(toastr,"Unexpected error")}finally{a&&(a.disabled=!1,a.dataset.originalText&&(a.innerHTML=a.dataset.originalText,delete a.dataset.originalText))}})}function B(r){D();const a=new T("#patientDocumentsTable",{retrieve:!0,ajax:{url:`/consultations/patient/${r}`,dataSrc:"",headers:{Accept:"application/json"}},layout:{topStart:null,topEnd:null,bottomStart:"pageLength",bottom2Start:"info",bottomEnd:"paging"},responsive:!0,searching:!0,lengthChange:!0,paging:!0,order:[[2,"desc"]],columnDefs:[{targets:-1,className:"flex justify-center",width:10}],columns:[{data:"document_type",title:"Document Type",render:e=>e?e.toUpperCase():""},{data:"created_at",title:"Date Added",render:e=>v(e)},{data:null,title:"Actions",orderable:!1,searchable:!1,render:(e,o,n)=>`
          <div class="w-full flex flex-row justify-start items-center gap-2">
            <a type="button"
              class="dt-action px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8"
              href="/consultations/${n.id??""}/${n.document_type??""}"
              target="_blank"
              rel="noopener noreferrer"
              >
              <i class="fa-solid fa-file fa-sm"></i>
              <span class="text-xs font-mono font-bold">PDF</span>
            </a>
             ${window.currentUserRole===2?`
              <button type="button"
                class="dt-action px-2 py-1 border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8"
                data-modal-open="edit-document-p"
                data-action="edit"
                data-id="${n.id??""}">
                <i class="fa-solid fa-pen fa-sm"></i>
              </button>
              <button type="button"
                class="dt-action px-2 py-1 border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md size-8"
                data-modal-open="delete-document"
                data-action="delete"
                data-id="${n.id??""}">
                <i class="fa-solid fa-trash fa-sm"></i>
              </button>
            `:""}
          </div>
        `}],drawCallback:function(){this.api().column(0,{search:"applied",order:"applied"}).nodes().each((e,o)=>{e.innerHTML=o+1})}});document.querySelector("#patientDocumentsTable tbody").addEventListener("click",e=>{var i;const o=e.target.closest(".dt-action");if(!o)return;const n=o.dataset.action,s=o.dataset.id;n==="delete"&&x(s),n==="edit"&&(I(s),(i=window.openModal)==null||i.call(window,"edit-document-p"))});const d=document.getElementById("documentSearch");d&&d.addEventListener("input",e=>{a.search(e.target.value).draw()}),window.refreshDocumentsTable=()=>a.ajax.reload(null,!1)}const y=document.getElementById("viewPid").getHTML();y&&B(y);
