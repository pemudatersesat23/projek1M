import { useState, useRef, useEffect, useCallback } from "react";

const P = '#673ab7';
const PL = '#ede7f6';
const G = '#5f6368';
const T = '#202124';
const PAGE = '#f0ebf8';

let _n = 100;
const uid = () => String(_n++);

const QTYPES = [
  { v: 'short', l: 'Short answer' },
  { v: 'paragraph', l: 'Paragraph' },
  null,
  { v: 'mc', l: 'Multiple choice' },
  { v: 'cb', l: 'Checkboxes' },
  { v: 'dd', l: 'Dropdown' },
  null,
  { v: 'file', l: 'File upload' },
  null,
  { v: 'scale', l: 'Linear scale' },
  { v: 'rating', l: 'Rating' },
  { v: 'mcgrid', l: 'Multiple choice grid' },
  { v: 'cbgrid', l: 'Checkbox grid' },
  null,
  { v: 'date', l: 'Date' },
  { v: 'time', l: 'Time' },
];

function mkQ(type = 'mc') {
  const hasOpts = ['mc', 'cb', 'dd'].includes(type);
  const hasGrid = ['mcgrid', 'cbgrid'].includes(type);
  return {
    id: uid(), type, title: '', required: false,
    opts: hasOpts ? [{ id: uid(), v: 'Option 1' }] : [],
    hasOther: false,
    scaleMin: 1, scaleMax: 5, scaleMinL: '', scaleMaxL: '',
    ratingMax: 5,
    rows: hasGrid ? [{ id: uid(), v: 'Row 1' }] : [],
    cols: hasGrid ? [{ id: uid(), v: 'Column 1' }] : [],
  };
}

// ─── SVG Icons ──────────────────────────────────────────────
const Icon = {
  Plus: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>,
  Import: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>,
  Title: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><line x1="12" y1="4" x2="12" y2="20"/></svg>,
  Image: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>,
  Video: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>,
  Section: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>,
  Copy: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>,
  Trash: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>,
  ChevDown: () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><polyline points="6 9 12 15 18 9"/></svg>,
  Close: () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>,
  Dots: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>,
  DotsH: () => <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/></svg>,
  Eye: () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>,
  Edit: () => <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>,
  Send: () => <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>,
  Grip: () => <svg width="20" height="12" viewBox="0 0 20 12" fill={G}><circle cx="7" cy="3" r="1.5"/><circle cx="13" cy="3" r="1.5"/><circle cx="7" cy="9" r="1.5"/><circle cx="13" cy="9" r="1.5"/></svg>,
  Star: ({ filled }) => <svg width="28" height="28" viewBox="0 0 24 24" fill={filled ? '#fbbc04' : 'none'} stroke={filled ? '#fbbc04' : G} strokeWidth="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>,
};

const iBtn = (onClick, icon, title, extra = {}) => (
  <button onClick={onClick} title={title} style={{
    background: 'none', border: 'none', cursor: 'pointer', padding: 8,
    borderRadius: '50%', color: G, display: 'flex', alignItems: 'center', justifyContent: 'center',
    transition: 'background .15s', ...extra,
  }}
    onMouseEnter={e => e.currentTarget.style.background = '#f5f5f5'}
    onMouseLeave={e => e.currentTarget.style.background = 'none'}>
    {icon}
  </button>
);

// ─── Toggle ─────────────────────────────────────────────────
function Toggle({ checked, onChange }) {
  return (
    <div onClick={e => { e.stopPropagation(); onChange(); }} style={{
      width: 38, height: 22, borderRadius: 11, cursor: 'pointer',
      background: checked ? P : '#bdbdbd', position: 'relative',
      transition: 'background .2s', flexShrink: 0,
    }}>
      <div style={{
        width: 18, height: 18, borderRadius: '50%', background: '#fff',
        position: 'absolute', top: 2,
        left: checked ? 18 : 2, transition: 'left .2s',
        boxShadow: '0 1px 3px rgba(0,0,0,.35)'
      }} />
    </div>
  );
}

// ─── Type Dropdown ───────────────────────────────────────────
function TypeDropdown({ value, onChange }) {
  const [open, setOpen] = useState(false);
  const ref = useRef();
  const current = QTYPES.find(t => t && t.v === value);

  useEffect(() => {
    const h = e => { if (ref.current && !ref.current.contains(e.target)) setOpen(false); };
    document.addEventListener('mousedown', h);
    return () => document.removeEventListener('mousedown', h);
  }, []);

  return (
    <div ref={ref} style={{ position: 'relative', userSelect: 'none', flexShrink: 0 }}>
      <div onClick={e => { e.stopPropagation(); setOpen(v => !v); }} style={{
        display: 'flex', alignItems: 'center', gap: 8,
        border: '1px solid #dadce0', borderRadius: 4, padding: '8px 12px',
        cursor: 'pointer', background: '#fff', minWidth: 200,
        justifyContent: 'space-between',
      }}>
        <span style={{ fontSize: 14, color: T }}>{current?.l}</span>
        <Icon.ChevDown />
      </div>
      {open && (
        <div style={{
          position: 'absolute', right: 0, top: 'calc(100% + 4px)', zIndex: 9999,
          background: '#fff', borderRadius: 4, boxShadow: '0 4px 20px rgba(0,0,0,.2)',
          minWidth: 240, maxHeight: 420, overflowY: 'auto',
        }}>
          {QTYPES.map((t, i) => t === null ? (
            <div key={i} style={{ height: 1, background: '#e0e0e0', margin: '4px 0' }} />
          ) : (
            <div key={t.v} onClick={e => { e.stopPropagation(); onChange(t.v); setOpen(false); }} style={{
              padding: '10px 16px', fontSize: 14, cursor: 'pointer', color: T,
              background: value === t.v ? PL : 'transparent',
              display: 'flex', alignItems: 'center',
            }}
              onMouseEnter={e => e.currentTarget.style.background = value === t.v ? PL : '#f5f5f5'}
              onMouseLeave={e => e.currentTarget.style.background = value === t.v ? PL : 'transparent'}
            >
              {t.l}
              {value === t.v && <svg style={{ marginLeft: 'auto' }} width="18" height="18" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="2.5"><polyline points="20 6 9 17 4 12" /></svg>}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

// ─── Options Editor (MC / CB / DD) ──────────────────────────
function OptionsEditor({ q, updateQ }) {
  const isMC = q.type === 'mc', isCB = q.type === 'cb', isDD = q.type === 'dd';
  const addOpt = () => updateQ({ opts: [...q.opts, { id: uid(), v: `Option ${q.opts.length + 1}` }] });
  const delOpt = oid => updateQ({ opts: q.opts.filter(o => o.id !== oid) });
  const editOpt = (oid, v) => updateQ({ opts: q.opts.map(o => o.id === oid ? { ...o, v } : o) });

  const Bullet = () => isMC
    ? <div style={{ width: 18, height: 18, borderRadius: '50%', border: '2px solid #bdbdbd', flexShrink: 0 }} />
    : isCB
      ? <div style={{ width: 18, height: 18, border: '2px solid #bdbdbd', borderRadius: 2, flexShrink: 0 }} />
      : null;

  return (
    <div style={{ marginTop: 8 }}>
      {q.opts.map((opt, i) => (
        <div key={opt.id} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 8 }}>
          {isDD ? <span style={{ color: G, fontSize: 14, minWidth: 20 }}>{i + 1}</span> : <Bullet />}
          <input value={opt.v} onChange={e => editOpt(opt.id, e.target.value)} placeholder={`Option ${i + 1}`}
            style={{ flex: 1, border: 'none', borderBottom: '1px dashed transparent', fontSize: 14, color: T, padding: '4px 0', outline: 'none', background: 'transparent' }}
            onFocus={e => e.target.style.borderBottom = '1px solid #bdbdbd'}
            onBlur={e => e.target.style.borderBottom = '1px dashed transparent'}
          />
          {q.opts.length > 1 && (
            <button onClick={() => delOpt(opt.id)} style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#bdbdbd', padding: 4, display: 'flex' }}>
              <Icon.Close />
            </button>
          )}
        </div>
      ))}
      {q.hasOther && (
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 8 }}>
          <Bullet />
          <span style={{ fontSize: 14, color: G, borderBottom: '1px dashed #bdbdbd', flex: 1, padding: '4px 0' }}>Other...</span>
          <button onClick={() => updateQ({ hasOther: false })} style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#bdbdbd', padding: 4, display: 'flex' }}>
            <Icon.Close />
          </button>
        </div>
      )}
      <div style={{ display: 'flex', alignItems: 'center', gap: 4, marginTop: 4 }}>
        {isDD ? <span style={{ color: G, fontSize: 14, minWidth: 20 }}>{q.opts.length + (q.hasOther ? 2 : 1)}</span> : <Bullet />}
        <button onClick={addOpt} style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: 14, color: T, padding: '4px 8px', borderRadius: 4 }}
          onMouseEnter={e => e.currentTarget.style.background = '#f5f5f5'}
          onMouseLeave={e => e.currentTarget.style.background = 'none'}>
          Add option
        </button>
        {!q.hasOther && !isDD && (
          <>
            <span style={{ fontSize: 14, color: G }}>or</span>
            <button onClick={() => updateQ({ hasOther: true })} style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: 14, color: P, padding: '4px 8px', borderRadius: 4 }}
              onMouseEnter={e => e.currentTarget.style.background = PL}
              onMouseLeave={e => e.currentTarget.style.background = 'none'}>
              add "Other"
            </button>
          </>
        )}
      </div>
    </div>
  );
}

// ─── Linear Scale Editor ────────────────────────────────────
function ScaleEditor({ q, updateQ }) {
  return (
    <div style={{ marginTop: 12 }}>
      <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 16 }}>
        <select value={q.scaleMin} onChange={e => updateQ({ scaleMin: +e.target.value })}
          style={{ border: '1px solid #dadce0', borderRadius: 4, padding: '6px 10px', fontSize: 14, color: T }}>
          {[0, 1].map(v => <option key={v} value={v}>{v}</option>)}
        </select>
        <span style={{ color: G, fontSize: 14 }}>to</span>
        <select value={q.scaleMax} onChange={e => updateQ({ scaleMax: +e.target.value })}
          style={{ border: '1px solid #dadce0', borderRadius: 4, padding: '6px 10px', fontSize: 14, color: T }}>
          {[2, 3, 4, 5, 6, 7, 8, 9, 10].map(v => <option key={v} value={v}>{v}</option>)}
        </select>
      </div>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
        {[
          { label: `${q.scaleMin} (optional)`, key: 'scaleMinL', val: q.scaleMinL },
          { label: `${q.scaleMax} (optional)`, key: 'scaleMaxL', val: q.scaleMaxL },
        ].map(({ label, key, val }) => (
          <div key={key}>
            <div style={{ fontSize: 12, color: G, marginBottom: 4 }}>{label}</div>
            <input value={val} onChange={e => updateQ({ [key]: e.target.value })} placeholder="Label"
              style={{ width: '100%', border: 'none', borderBottom: '1px solid #bdbdbd', fontSize: 14, padding: '4px 0', outline: 'none', background: 'transparent', color: T }}
              onFocus={e => e.target.style.borderBottom = `2px solid ${P}`}
              onBlur={e => e.target.style.borderBottom = '1px solid #bdbdbd'}
            />
          </div>
        ))}
      </div>
    </div>
  );
}

// ─── Rating Editor ──────────────────────────────────────────
function RatingEditor({ q, updateQ }) {
  return (
    <div style={{ marginTop: 12 }}>
      <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 12 }}>
        <span style={{ fontSize: 14, color: G }}>Count:</span>
        <select value={q.ratingMax} onChange={e => updateQ({ ratingMax: +e.target.value })}
          style={{ border: '1px solid #dadce0', borderRadius: 4, padding: '6px 10px', fontSize: 14, color: T }}>
          {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map(v => <option key={v} value={v}>{v}</option>)}
        </select>
        <span style={{ fontSize: 14, color: G }}>stars</span>
      </div>
      <div style={{ display: 'flex', gap: 2 }}>
        {Array.from({ length: q.ratingMax }).map((_, i) => <Icon.Star key={i} filled={false} />)}
      </div>
    </div>
  );
}

// ─── Grid Editor ────────────────────────────────────────────
function GridEditor({ q, updateQ }) {
  const isCB = q.type === 'cbgrid';
  const addRow = () => updateQ({ rows: [...q.rows, { id: uid(), v: `Row ${q.rows.length + 1}` }] });
  const addCol = () => updateQ({ cols: [...q.cols, { id: uid(), v: `Column ${q.cols.length + 1}` }] });
  const delRow = rid => updateQ({ rows: q.rows.filter(r => r.id !== rid) });
  const delCol = cid => updateQ({ cols: q.cols.filter(c => c.id !== cid) });
  const editRow = (rid, v) => updateQ({ rows: q.rows.map(r => r.id === rid ? { ...r, v } : r) });
  const editCol = (cid, v) => updateQ({ cols: q.cols.map(c => c.id === cid ? { ...c, v } : c) });

  const Bul = () => isCB
    ? <div style={{ width: 14, height: 14, border: '2px solid #bdbdbd', borderRadius: 2, flexShrink: 0 }} />
    : <div style={{ width: 14, height: 14, borderRadius: '50%', border: '2px solid #bdbdbd', flexShrink: 0 }} />;

  const ColList = ({ items, onEdit, onDel, onAdd, label }) => (
    <div>
      <div style={{ fontSize: 13, fontWeight: 500, color: G, marginBottom: 8 }}>{label}</div>
      {items.map((r, i) => (
        <div key={r.id} style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 8 }}>
          <Bul />
          <input value={r.v} onChange={e => onEdit(r.id, e.target.value)}
            style={{ flex: 1, border: 'none', borderBottom: '1px dashed transparent', fontSize: 14, outline: 'none', padding: '2px 0', background: 'transparent', color: T }}
            onFocus={e => e.target.style.borderBottom = '1px solid #bdbdbd'}
            onBlur={e => e.target.style.borderBottom = '1px dashed transparent'}
          />
          {items.length > 1 && (
            <button onClick={() => onDel(r.id)} style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#bdbdbd', padding: 2, display: 'flex' }}>
              <Icon.Close />
            </button>
          )}
        </div>
      ))}
      <button onClick={onAdd} style={{ fontSize: 14, color: G, background: 'none', border: 'none', cursor: 'pointer', padding: '4px 0' }}
        onMouseEnter={e => e.currentTarget.style.color = T}
        onMouseLeave={e => e.currentTarget.style.color = G}>
        Add {label.toLowerCase().replace('s', '')}
      </button>
    </div>
  );

  return (
    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 24, marginTop: 12 }}>
      <ColList items={q.rows} onEdit={editRow} onDel={delRow} onAdd={addRow} label="Rows" />
      <ColList items={q.cols} onEdit={editCol} onDel={delCol} onAdd={addCol} label="Columns" />
    </div>
  );
}

// ─── Question Body (View mode in collapsed card) ─────────────
function QBody({ q }) {
  const hasOpts = ['mc', 'cb', 'dd'].includes(q.type);
  return (
    <div style={{ marginTop: 8 }}>
      {q.type === 'short' && <div style={{ width: '60%', borderBottom: '1px dashed #bdbdbd', padding: '4px 0', fontSize: 14, color: '#aaa' }}>Short answer text</div>}
      {q.type === 'paragraph' && <div style={{ width: '100%', borderBottom: '1px dashed #bdbdbd', padding: '4px 0', fontSize: 14, color: '#aaa' }}>Long answer text</div>}
      {hasOpts && q.opts.slice(0, 4).map((o, i) => (
        <div key={o.id} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 6 }}>
          {q.type === 'mc' && <div style={{ width: 16, height: 16, borderRadius: '50%', border: '2px solid #bdbdbd', flexShrink: 0 }} />}
          {q.type === 'cb' && <div style={{ width: 16, height: 16, border: '2px solid #bdbdbd', borderRadius: 2, flexShrink: 0 }} />}
          {q.type === 'dd' && <span style={{ color: G, fontSize: 13, minWidth: 16 }}>{i + 1}</span>}
          <span style={{ fontSize: 14, color: T }}>{o.v}</span>
        </div>
      ))}
      {hasOpts && q.opts.length > 4 && <span style={{ fontSize: 13, color: G }}>+{q.opts.length - 4} more options</span>}
      {hasOpts && q.hasOther && <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
        {q.type === 'mc' && <div style={{ width: 16, height: 16, borderRadius: '50%', border: '2px solid #bdbdbd', flexShrink: 0 }} />}
        {q.type === 'cb' && <div style={{ width: 16, height: 16, border: '2px solid #bdbdbd', borderRadius: 2, flexShrink: 0 }} />}
        <span style={{ fontSize: 14, color: G }}>Other...</span>
      </div>}
      {q.type === 'scale' && <div style={{ fontSize: 14, color: G }}>{q.scaleMin} ———————— {q.scaleMax}{q.scaleMinL ? ` (${q.scaleMinL})` : ''}{q.scaleMaxL ? ` – (${q.scaleMaxL})` : ''}</div>}
      {q.type === 'rating' && <div style={{ display: 'flex', gap: 2 }}>{Array.from({ length: q.ratingMax }).map((_, i) => <Icon.Star key={i} filled={false} />)}</div>}
      {['mcgrid', 'cbgrid'].includes(q.type) && <div style={{ fontSize: 14, color: G }}>{q.rows.length} rows × {q.cols.length} columns</div>}
      {q.type === 'file' && <div style={{ border: '1px dashed #bdbdbd', borderRadius: 4, padding: '12px 16px', fontSize: 14, color: '#aaa', marginTop: 4 }}>Add file</div>}
      {q.type === 'date' && <input type="date" disabled style={{ border: 'none', borderBottom: '1px solid #bdbdbd', padding: '4px 0', fontSize: 14, color: G, background: 'transparent' }} />}
      {q.type === 'time' && <input type="time" disabled style={{ border: 'none', borderBottom: '1px solid #bdbdbd', padding: '4px 0', fontSize: 14, color: G, background: 'transparent' }} />}
    </div>
  );
}

// ─── Question Card ───────────────────────────────────────────
function QuestionCard({ q, active, onClick, onUpdate, onDelete, onDuplicate }) {
  const upd = updates => onUpdate(q.id, updates);
  const changeType = type => {
    const hasOpts = ['mc', 'cb', 'dd'].includes(type);
    const hasGrid = ['mcgrid', 'cbgrid'].includes(type);
    upd({
      type,
      opts: hasOpts ? (q.opts.length ? q.opts : [{ id: uid(), v: 'Option 1' }]) : [],
      hasOther: hasOpts ? q.hasOther : false,
      rows: hasGrid ? (q.rows.length ? q.rows : [{ id: uid(), v: 'Row 1' }]) : [],
      cols: hasGrid ? (q.cols.length ? q.cols : [{ id: uid(), v: 'Column 1' }]) : [],
    });
  };

  return (
    <div onClick={onClick} style={{
      background: '#fff', borderRadius: 8, marginBottom: 12,
      border: '1px solid #e0e0e0',
      borderLeft: active ? `4px solid ${P}` : '1px solid #e0e0e0',
      boxShadow: active ? '0 2px 10px rgba(0,0,0,.12)' : '0 1px 3px rgba(0,0,0,.06)',
      cursor: active ? 'default' : 'pointer',
      overflow: 'hidden', transition: 'box-shadow .2s, border-left .1s',
    }}>
      {active && (
        <div style={{ display: 'flex', justifyContent: 'center', padding: '6px 0 0', cursor: 'grab' }}>
          <Icon.Grip />
        </div>
      )}
      <div style={{ padding: active ? '12px 24px 20px' : '16px 24px' }}>
        {/* Header row */}
        <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12, marginBottom: active ? 16 : 8 }}>
          {active ? (
            <input value={q.title} onChange={e => upd({ title: e.target.value })} placeholder="Question"
              onClick={e => e.stopPropagation()}
              style={{
                flex: 1, border: 'none', borderBottom: `1px solid ${active ? '#bdbdbd' : 'transparent'}`,
                fontSize: 15, color: T, padding: '4px 0', outline: 'none',
                background: '#f8f9fa', borderRadius: '4px 4px 0 0', paddingLeft: 8,
              }}
            />
          ) : (
            <span style={{ flex: 1, fontSize: 15, color: q.title ? T : G, padding: '4px 0' }}>
              {q.title || 'Question'}
              {q.required && <span style={{ color: '#d93025', marginLeft: 4 }}>*</span>}
            </span>
          )}
          {active
            ? <TypeDropdown value={q.type} onChange={changeType} />
            : <span style={{ fontSize: 12, color: G, flexShrink: 0, marginTop: 6 }}>{QTYPES.find(t => t && t.v === q.type)?.l}</span>
          }
        </div>

        {/* Body */}
        {active ? (
          <>
            {['mc', 'cb', 'dd'].includes(q.type) && <OptionsEditor q={q} updateQ={upd} />}
            {q.type === 'short' && <div style={{ borderBottom: '1px dashed #bdbdbd', padding: '4px 0', fontSize: 14, color: '#aaa', width: '60%' }}>Short answer text</div>}
            {q.type === 'paragraph' && <div style={{ borderBottom: '1px dashed #bdbdbd', padding: '4px 0', fontSize: 14, color: '#aaa', width: '100%' }}>Long answer text</div>}
            {q.type === 'scale' && <ScaleEditor q={q} updateQ={upd} />}
            {q.type === 'rating' && <RatingEditor q={q} updateQ={upd} />}
            {['mcgrid', 'cbgrid'].includes(q.type) && <GridEditor q={q} updateQ={upd} />}
            {q.type === 'file' && <div style={{ border: '1px dashed #bdbdbd', borderRadius: 4, padding: '16px', textAlign: 'center', fontSize: 14, color: '#aaa', marginTop: 8 }}>File upload field</div>}
            {q.type === 'date' && <input type="date" disabled style={{ border: 'none', borderBottom: '1px solid #bdbdbd', fontSize: 14, color: G, background: 'transparent', marginTop: 8 }} />}
            {q.type === 'time' && <input type="time" disabled style={{ border: 'none', borderBottom: '1px solid #bdbdbd', fontSize: 14, color: G, background: 'transparent', marginTop: 8 }} />}
          </>
        ) : (
          <QBody q={q} />
        )}

        {/* Footer */}
        {active && (
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'flex-end', gap: 4, marginTop: 20, paddingTop: 12, borderTop: '1px solid #e0e0e0' }}>
            {iBtn(e => { e.stopPropagation(); onDuplicate(q.id); }, <Icon.Copy />, 'Duplicate')}
            {iBtn(e => { e.stopPropagation(); onDelete(q.id); }, <Icon.Trash />, 'Delete')}
            <div style={{ width: 1, height: 24, background: '#e0e0e0', margin: '0 4px' }} />
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
              <span style={{ fontSize: 14, color: T }}>Required</span>
              <Toggle checked={q.required} onChange={() => upd({ required: !q.required })} />
            </div>
            {iBtn(e => e.stopPropagation(), <Icon.Dots />, 'More options')}
          </div>
        )}
      </div>
    </div>
  );
}

// ─── Preview Mode ────────────────────────────────────────────
function Preview({ formTitle, formDesc, questions }) {
  const [ans, setAns] = useState({});
  const [submitted, setSubmitted] = useState(false);

  if (submitted) {
    return (
      <div style={{ background: '#fff', borderRadius: 8, padding: 32, textAlign: 'center', boxShadow: '0 1px 3px rgba(0,0,0,.1)' }}>
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke={P} strokeWidth="1.5" style={{ marginBottom: 16 }}>
          <path d="M22 11.08V12a10 10 0 11-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        <div style={{ fontSize: 24, color: T, marginBottom: 8 }}>Your response has been recorded.</div>
        <button onClick={() => { setSubmitted(false); setAns({}); }} style={{ color: P, background: 'none', border: 'none', cursor: 'pointer', fontSize: 14, marginTop: 16 }}>Submit another response</button>
      </div>
    );
  }

  const renderField = q => {
    const set = v => setAns(p => ({ ...p, [q.id]: v }));
    const val = ans[q.id];

    switch (q.type) {
      case 'short': return <input value={val || ''} onChange={e => set(e.target.value)} style={{ width: '100%', border: 'none', borderBottom: '1px solid #bdbdbd', fontSize: 14, padding: '4px 0', outline: 'none', background: 'transparent', color: T }}
        onFocus={e => e.target.style.borderBottom = `2px solid ${P}`} onBlur={e => e.target.style.borderBottom = '1px solid #bdbdbd'} />;
      case 'paragraph': return <textarea value={val || ''} onChange={e => set(e.target.value)} rows={4}
        style={{ width: '100%', border: 'none', borderBottom: '1px solid #bdbdbd', fontSize: 14, padding: '4px 0', outline: 'none', background: 'transparent', resize: 'none', color: T }}
        onFocus={e => e.target.style.borderBottom = `2px solid ${P}`} onBlur={e => e.target.style.borderBottom = '1px solid #bdbdbd'} />;
      case 'mc': return (
        <div>
          {q.opts.map(o => (
            <label key={o.id} style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 12, cursor: 'pointer' }}>
              <input type="radio" name={q.id} value={o.id} checked={val === o.id} onChange={() => set(o.id)} style={{ accentColor: P, width: 18, height: 18 }} />
              <span style={{ fontSize: 14, color: T }}>{o.v}</span>
            </label>
          ))}
          {q.hasOther && (
            <label style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 12, cursor: 'pointer' }}>
              <input type="radio" name={q.id} value="__other" checked={val === '__other'} onChange={() => set('__other')} style={{ accentColor: P, width: 18, height: 18 }} />
              <span style={{ fontSize: 14, color: T }}>Other:</span>
              <input style={{ flex: 1, border: 'none', borderBottom: '1px solid #bdbdbd', fontSize: 14, outline: 'none', background: 'transparent' }} />
            </label>
          )}
        </div>
      );
      case 'cb': return (
        <div>
          {q.opts.map(o => (
            <label key={o.id} style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 12, cursor: 'pointer' }}>
              <input type="checkbox" checked={(val || []).includes(o.id)}
                onChange={e => set(e.target.checked ? [...(val || []), o.id] : (val || []).filter(v => v !== o.id))}
                style={{ accentColor: P, width: 18, height: 18 }} />
              <span style={{ fontSize: 14, color: T }}>{o.v}</span>
            </label>
          ))}
          {q.hasOther && (
            <label style={{ display: 'flex', alignItems: 'center', gap: 12, cursor: 'pointer' }}>
              <input type="checkbox" checked={(val || []).includes('__other')}
                onChange={e => set(e.target.checked ? [...(val || []), '__other'] : (val || []).filter(v => v !== '__other'))}
                style={{ accentColor: P, width: 18, height: 18 }} />
              <span style={{ fontSize: 14, color: T }}>Other:</span>
              <input style={{ flex: 1, border: 'none', borderBottom: '1px solid #bdbdbd', fontSize: 14, outline: 'none', background: 'transparent' }} />
            </label>
          )}
        </div>
      );
      case 'dd': return (
        <select value={val || ''} onChange={e => set(e.target.value)}
          style={{ border: 'none', borderBottom: `1px solid #bdbdbd`, fontSize: 14, padding: '4px 0', background: 'transparent', outline: 'none', minWidth: 200, color: val ? T : G }}>
          <option value="" disabled>Choose</option>
          {q.opts.map(o => <option key={o.id} value={o.id}>{o.v}</option>)}
        </select>
      );
      case 'scale': return (
        <div style={{ marginTop: 8 }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
            {q.scaleMinL && <span style={{ fontSize: 13, color: G, marginRight: 8 }}>{q.scaleMinL}</span>}
            {Array.from({ length: q.scaleMax - q.scaleMin + 1 }, (_, i) => i + q.scaleMin).map(n => (
              <label key={n} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 4, cursor: 'pointer' }}>
                <input type="radio" name={q.id} value={n} checked={+val === n} onChange={() => set(n)} style={{ accentColor: P, width: 18, height: 18 }} />
                <span style={{ fontSize: 13, color: G }}>{n}</span>
              </label>
            ))}
            {q.scaleMaxL && <span style={{ fontSize: 13, color: G, marginLeft: 8 }}>{q.scaleMaxL}</span>}
          </div>
        </div>
      );
      case 'rating': return (
        <div style={{ display: 'flex', gap: 4, marginTop: 8 }}>
          {Array.from({ length: q.ratingMax }, (_, i) => i + 1).map(n => (
            <div key={n} onClick={() => set(n)} style={{ cursor: 'pointer' }}>
              <Icon.Star filled={n <= (val || 0)} />
            </div>
          ))}
        </div>
      );
      case 'mcgrid': return (
        <div style={{ overflowX: 'auto' }}>
          <table style={{ borderCollapse: 'collapse', width: '100%' }}>
            <thead>
              <tr>
                <th style={{ width: 160 }} />
                {q.cols.map(c => <th key={c.id} style={{ padding: '8px 16px', fontSize: 13, color: G, fontWeight: 400, textAlign: 'center' }}>{c.v}</th>)}
              </tr>
            </thead>
            <tbody>
              {q.rows.map(r => (
                <tr key={r.id}>
                  <td style={{ fontSize: 14, color: T, padding: '8px 0' }}>{r.v}</td>
                  {q.cols.map(c => (
                    <td key={c.id} style={{ textAlign: 'center', padding: '8px 16px' }}>
                      <input type="radio" name={`${q.id}_${r.id}`} value={c.id}
                        checked={((val || {})[r.id]) === c.id}
                        onChange={() => set({ ...(val || {}), [r.id]: c.id })}
                        style={{ accentColor: P, width: 18, height: 18 }} />
                    </td>
                  ))}
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      );
      case 'cbgrid': return (
        <div style={{ overflowX: 'auto' }}>
          <table style={{ borderCollapse: 'collapse', width: '100%' }}>
            <thead>
              <tr>
                <th style={{ width: 160 }} />
                {q.cols.map(c => <th key={c.id} style={{ padding: '8px 16px', fontSize: 13, color: G, fontWeight: 400, textAlign: 'center' }}>{c.v}</th>)}
              </tr>
            </thead>
            <tbody>
              {q.rows.map(r => (
                <tr key={r.id}>
                  <td style={{ fontSize: 14, color: T, padding: '8px 0' }}>{r.v}</td>
                  {q.cols.map(c => (
                    <td key={c.id} style={{ textAlign: 'center', padding: '8px 16px' }}>
                      <input type="checkbox"
                        checked={((val || {})[r.id] || []).includes(c.id)}
                        onChange={e => {
                          const curr = val || {}, row = curr[r.id] || [];
                          set({ ...curr, [r.id]: e.target.checked ? [...row, c.id] : row.filter(v => v !== c.id) });
                        }}
                        style={{ accentColor: P, width: 18, height: 18 }} />
                    </td>
                  ))}
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      );
      case 'file': return (
        <button style={{ border: `1px solid ${P}`, color: P, background: 'none', borderRadius: 4, padding: '8px 24px', cursor: 'pointer', fontSize: 14, marginTop: 8 }}>
          Add file
        </button>
      );
      case 'date': return <input type="date" style={{ border: 'none', borderBottom: `1px solid #bdbdbd`, fontSize: 14, padding: '4px 0', background: 'transparent', marginTop: 8 }} />;
      case 'time': return <input type="time" style={{ border: 'none', borderBottom: `1px solid #bdbdbd`, fontSize: 14, padding: '4px 0', background: 'transparent', marginTop: 8 }} />;
      default: return null;
    }
  };

  return (
    <div>
      <div style={{ background: '#fff', borderRadius: 8, overflow: 'hidden', marginBottom: 16, boxShadow: '0 1px 3px rgba(0,0,0,.1)' }}>
        <div style={{ height: 10, background: P }} />
        <div style={{ padding: '24px 24px 20px' }}>
          <div style={{ fontSize: 30, color: T, fontWeight: 400, marginBottom: 8 }}>{formTitle || 'Untitled form'}</div>
          {formDesc && <div style={{ fontSize: 14, color: G }}>{formDesc}</div>}
          <div style={{ fontSize: 13, color: '#d93025', marginTop: 12 }}>* Required question</div>
        </div>
      </div>
      {questions.map(q => (
        <div key={q.id} style={{ background: '#fff', borderRadius: 8, marginBottom: 12, padding: 24, boxShadow: '0 1px 3px rgba(0,0,0,.1)' }}>
          <div style={{ fontSize: 15, color: T, marginBottom: 16 }}>
            {q.title || 'Question'}
            {q.required && <span style={{ color: '#d93025', marginLeft: 4 }}>*</span>}
          </div>
          {renderField(q)}
        </div>
      ))}
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginTop: 8 }}>
        <button onClick={() => setSubmitted(true)} style={{
          background: P, color: '#fff', border: 'none', borderRadius: 4,
          padding: '12px 28px', fontSize: 14, cursor: 'pointer', fontWeight: 500,
        }}>
          Submit
        </button>
        <button onClick={() => setAns({})} style={{ color: P, background: 'none', border: 'none', fontSize: 14, cursor: 'pointer', fontWeight: 500 }}>
          Clear form
        </button>
      </div>
    </div>
  );
}

// ─── Right Sidebar ───────────────────────────────────────────
function Sidebar({ onAdd, onTitle, onImage, onVideo, onSection }) {
  const btns = [
    { icon: <Icon.Plus />, fn: onAdd, label: 'Add question' },
    { icon: <Icon.Import />, fn: () => { }, label: 'Import questions' },
    { icon: <Icon.Title />, fn: onTitle, label: 'Add title & description' },
    { icon: <Icon.Image />, fn: onImage, label: 'Add image' },
    { icon: <Icon.Video />, fn: onVideo, label: 'Add video' },
    { icon: <Icon.Section />, fn: onSection, label: 'Add section' },
  ];
  return (
    <div style={{ position: 'sticky', top: 80, background: '#fff', borderRadius: 8, padding: '4px 0', boxShadow: '0 1px 4px rgba(0,0,0,.12)', display: 'flex', flexDirection: 'column', alignItems: 'center', width: 48 }}>
      {btns.map((b, i) => (
        <button key={i} onClick={b.fn} title={b.label} style={{
          background: 'none', border: 'none', cursor: 'pointer', padding: 12,
          color: G, display: 'flex', width: '100%', justifyContent: 'center',
          borderRadius: 4, transition: 'background .15s',
        }}
          onMouseEnter={e => { e.currentTarget.style.background = '#f5f5f5'; e.currentTarget.style.color = T; }}
          onMouseLeave={e => { e.currentTarget.style.background = 'none'; e.currentTarget.style.color = G; }}>
          {b.icon}
        </button>
      ))}
    </div>
  );
}

// ─── Top Bar ─────────────────────────────────────────────────
function TopBar({ formTitle, setFormTitle, mode, setMode }) {
  return (
    <div style={{
      background: '#fff', borderBottom: '1px solid #e0e0e0', padding: '0 20px',
      display: 'flex', alignItems: 'center', height: 64,
      position: 'sticky', top: 0, zIndex: 100, boxShadow: '0 1px 4px rgba(0,0,0,.08)',
    }}>
      <svg width="40" height="40" viewBox="0 0 40 40" style={{ flexShrink: 0, marginRight: 8 }}>
        <rect width="40" height="40" rx="6" fill={P} />
        <rect x="10" y="13" width="20" height="2.5" rx="1.25" fill="white" />
        <rect x="10" y="18.75" width="20" height="2.5" rx="1.25" fill="white" />
        <rect x="10" y="24.5" width="12" height="2.5" rx="1.25" fill="white" />
      </svg>
      <input value={formTitle} onChange={e => setFormTitle(e.target.value)}
        style={{ fontSize: 18, border: 'none', outline: 'none', color: T, background: 'transparent', borderBottom: '1px solid transparent', padding: '4px 6px', maxWidth: 360, borderRadius: '4px 4px 0 0' }}
        onFocus={e => e.target.style.borderBottom = '2px solid #bdbdbd'}
        onBlur={e => e.target.style.borderBottom = '1px solid transparent'}
      />
      <div style={{ flex: 1 }} />
      <button onClick={() => setMode(m => m === 'edit' ? 'preview' : 'edit')} style={{
        display: 'flex', alignItems: 'center', gap: 8, background: 'none', border: 'none',
        cursor: 'pointer', padding: '8px 14px', borderRadius: 4, fontSize: 14, color: G, fontWeight: 500,
      }}
        onMouseEnter={e => e.currentTarget.style.background = '#f5f5f5'}
        onMouseLeave={e => e.currentTarget.style.background = 'none'}
        title={mode === 'edit' ? 'Preview form' : 'Back to edit'}>
        {mode === 'edit' ? <><Icon.Eye />&nbsp;Preview</> : <><Icon.Edit />&nbsp;Edit</>}
      </button>
      <div style={{ width: 1, height: 32, background: '#e0e0e0', margin: '0 8px' }} />
      {iBtn(() => { }, <Icon.DotsH />, 'More options')}
      <button style={{ background: P, color: '#fff', border: 'none', borderRadius: 20, padding: '8px 20px', cursor: 'pointer', fontSize: 14, fontWeight: 500, marginLeft: 8, display: 'flex', alignItems: 'center', gap: 8 }}>
        <Icon.Send />&nbsp;Send
      </button>
    </div>
  );
}

// ─── Export JSON ─────────────────────────────────────────────
function ExportPanel({ formTitle, formDesc, questions }) {
  const [copied, setCopied] = useState(false);
  const data = { title: formTitle, description: formDesc, questions: questions.map(q => ({ ...q })) };
  const json = JSON.stringify(data, null, 2);
  const copy = async () => {
    try {
      if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(json);
      } else {
        const ta = document.createElement('textarea');
        ta.value = json;
        ta.style.position = 'fixed';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
      }
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
    } catch (err) {
      console.error('Copy failed:', err);
      alert('Gagal copy otomatis. Silakan blok teks JSON lalu copy manual.');
    }
  };
  return (
    <div style={{ background: '#fff', borderRadius: 8, padding: 20, boxShadow: '0 1px 3px rgba(0,0,0,.1)', marginTop: 12 }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 12 }}>
        <span style={{ fontSize: 14, fontWeight: 500, color: T }}>Export JSON (Laravel)</span>
        <button onClick={copy} style={{ background: copied ? '#e8f5e9' : PL, color: copied ? '#2e7d32' : P, border: 'none', borderRadius: 4, padding: '6px 14px', cursor: 'pointer', fontSize: 13, fontWeight: 500 }}>
          {copied ? '✓ Copied!' : 'Copy JSON'}
        </button>
      </div>
      <pre style={{ background: '#f8f9fa', borderRadius: 4, padding: 12, fontSize: 11, overflowX: 'auto', maxHeight: 200, color: T, margin: 0 }}>
        {json}
      </pre>
    </div>
  );
}

// ─── Main App ────────────────────────────────────────────────
export default function FormBuilder() {
  const [formTitle, setFormTitle] = useState('Untitled form');
  const [formDesc, setFormDesc] = useState('');
  const [questions, setQuestions] = useState([mkQ('mc')]);
  const [activeId, setActiveId] = useState(null);
  const [mode, setMode] = useState('edit');
  const [showExport, setShowExport] = useState(false);

  const addQ = useCallback((afterId = null, type = 'mc') => {
    const q = mkQ(type);
    setQuestions(prev => {
      if (!afterId) return [...prev, q];
      const idx = prev.findIndex(p => p.id === afterId);
      const next = [...prev];
      next.splice(idx + 1, 0, q);
      return next;
    });
    setActiveId(q.id);
  }, []);

  const updateQ = useCallback((id, updates) => setQuestions(prev => prev.map(q => q.id === id ? { ...q, ...updates } : q)), []);
  const deleteQ = useCallback((id) => {
    setQuestions(prev => {
      const next = prev.filter(q => q.id !== id);
      return next.length ? next : [mkQ()];
    });
    setActiveId(null);
  }, []);
  const duplicateQ = useCallback((id) => {
    setQuestions(prev => {
      const idx = prev.findIndex(q => q.id === id);
      const src = prev[idx];
      const dup = { ...src, id: uid(), title: src.title, opts: src.opts.map(o => ({ ...o, id: uid() })), rows: src.rows.map(r => ({ ...r, id: uid() })), cols: src.cols.map(c => ({ ...c, id: uid() })) };
      const next = [...prev];
      next.splice(idx + 1, 0, dup);
      return next;
    });
  }, []);

  const handleBgClick = e => { if (e.target === e.currentTarget) setActiveId(null); };

  return (
    <div style={{ background: PAGE, minHeight: '100vh', fontFamily: "'Google Sans', Roboto, Arial, sans-serif" }}>
      <TopBar formTitle={formTitle} setFormTitle={setFormTitle} mode={mode} setMode={setMode} />

      {mode === 'preview' ? (
        <div style={{ maxWidth: 640, margin: '0 auto', padding: '24px 16px' }}>
          <Preview formTitle={formTitle} formDesc={formDesc} questions={questions} />
        </div>
      ) : (
        <div onClick={handleBgClick} style={{ display: 'flex', justifyContent: 'center', alignItems: 'flex-start', gap: 12, padding: '24px 16px', maxWidth: 900, margin: '0 auto' }}>
          <div style={{ flex: 1, maxWidth: 640 }}>
            {/* Form Header */}
            <div style={{ background: '#fff', borderRadius: 8, overflow: 'hidden', marginBottom: 12, boxShadow: '0 1px 3px rgba(0,0,0,.1)', borderTop: `10px solid ${P}` }}>
              <div style={{ padding: '20px 24px' }}>
                <input value={formTitle} onChange={e => setFormTitle(e.target.value)} placeholder="Form title"
                  style={{ width: '100%', border: 'none', borderBottom: '1px solid transparent', fontSize: 28, color: T, outline: 'none', padding: '4px 0', background: 'transparent', fontWeight: 400 }}
                  onFocus={e => e.target.style.borderBottom = `2px solid ${P}`}
                  onBlur={e => e.target.style.borderBottom = '1px solid transparent'}
                />
                <input value={formDesc} onChange={e => setFormDesc(e.target.value)} placeholder="Form description"
                  style={{ width: '100%', border: 'none', borderBottom: '1px solid transparent', fontSize: 14, color: G, outline: 'none', padding: '8px 0 4px', background: 'transparent', marginTop: 8 }}
                  onFocus={e => e.target.style.borderBottom = `1px solid ${P}`}
                  onBlur={e => e.target.style.borderBottom = '1px solid transparent'}
                />
              </div>
            </div>

            {/* Questions */}
            {questions.map(q => (
              <QuestionCard key={q.id} q={q} active={activeId === q.id}
                onClick={() => setActiveId(q.id)}
                onUpdate={updateQ} onDelete={deleteQ} onDuplicate={duplicateQ}
              />
            ))}

            {/* Export toggle */}
            <button onClick={() => setShowExport(v => !v)} style={{
              background: 'none', border: `1px dashed ${P}`, color: P, borderRadius: 8,
              padding: '10px 20px', cursor: 'pointer', fontSize: 14, width: '100%',
              marginTop: 4, fontWeight: 500,
            }}>
              {showExport ? '▲ Hide' : '▼ Export'} JSON for Laravel
            </button>
            {showExport && <ExportPanel formTitle={formTitle} formDesc={formDesc} questions={questions} />}
          </div>

          {/* Sidebar */}
          <Sidebar
            onAdd={() => addQ(activeId)}
            onTitle={() => { }}
            onImage={() => { }}
            onVideo={() => { }}
            onSection={() => { }}
          />
        </div>
      )}
    </div>
  );
}
