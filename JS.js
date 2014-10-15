// cross-browser preventdefault function
function MyPreventDefault(ev){
  if (ev.preventDefault) { ev.preventDefault(); } else { ev.returnValue = false; }
}
