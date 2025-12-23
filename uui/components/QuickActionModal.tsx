
import React from 'react';
import { X, Check, AlertTriangle, Info } from 'lucide-react';

interface QuickActionModalProps {
  isOpen: boolean;
  onClose: () => void;
  title: string;
  type?: 'info' | 'warning' | 'success';
  children: React.ReactNode;
  onConfirm?: () => void;
  confirmLabel?: string;
}

const QuickActionModal: React.FC<QuickActionModalProps> = ({ 
  isOpen, 
  onClose, 
  title, 
  type = 'info', 
  children, 
  onConfirm,
  confirmLabel = "Confirmer"
}) => {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 overflow-hidden">
      {/* Backdrop */}
      <div 
        className="absolute inset-0 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300"
        onClick={onClose}
      />
      
      {/* Modal Card */}
      <div className="relative bg-white w-full max-w-lg rounded-[32px] shadow-2xl shadow-slate-900/20 overflow-hidden animate-in zoom-in-95 duration-200">
        <div className="p-8">
          <div className="flex items-center justify-between mb-6">
            <h3 className="text-xl font-black text-slate-900 flex items-center gap-3">
              <div className={`p-2 rounded-xl ${
                type === 'warning' ? 'bg-rose-50 text-rose-500' : 
                type === 'success' ? 'bg-emerald-50 text-emerald-500' : 'bg-indigo-50 text-indigo-500'
              }`}>
                {type === 'warning' ? <AlertTriangle size={20} /> : type === 'success' ? <Check size={20} /> : <Info size={20} />}
              </div>
              {title}
            </h3>
            <button onClick={onClose} className="p-2 hover:bg-slate-100 rounded-full transition-colors text-slate-400">
              <X size={20} />
            </button>
          </div>

          <div className="space-y-4">
            {children}
          </div>

          <div className="mt-8 flex gap-3">
            <button 
              onClick={onClose}
              className="flex-1 py-3.5 bg-slate-50 text-slate-500 font-bold rounded-2xl hover:bg-slate-100 transition-colors"
            >
              Annuler
            </button>
            {onConfirm && (
              <button 
                onClick={() => { onConfirm(); onClose(); }}
                className={`flex-1 py-3.5 text-white font-black rounded-2xl shadow-lg transition-all active:scale-95 ${
                  type === 'warning' ? 'bg-rose-600 shadow-rose-100 hover:bg-rose-700' : 'bg-indigo-600 shadow-indigo-100 hover:bg-indigo-700'
                }`}
              >
                {confirmLabel}
              </button>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default QuickActionModal;
