import React from 'react';
import { createRoot } from 'react-dom/client';
import FormBuilder from './FormBuilder.jsx';

createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <FormBuilder />
  </React.StrictMode>
);
