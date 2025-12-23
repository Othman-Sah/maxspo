
import React, { useState } from 'react';
import { 
  ShoppingCart, 
  Search, 
  Trash2, 
  Minus, 
  Plus, 
  Zap, 
  Banknote, 
  CreditCard,
  Package,
  CheckCircle2,
  X,
  PlusCircle
} from 'lucide-react';
import { MOCK_POS_ITEMS } from '../constants';
import { POSItem } from '../types';

interface POSViewProps {
  onCheckout: (subtotal: number, items: number) => void;
  onAddItem: () => void;
}

const POSView: React.FC<POSViewProps> = ({ onCheckout, onAddItem }) => {
  const [cart, setCart] = useState<{item: POSItem, quantity: number}[]>([]);
  const [activeCategory, setActiveCategory] = useState<'all' | 'snack' | 'boisson' | 'complement'>('all');
  const [searchQuery, setSearchQuery] = useState('');

  const addToCart = (item: POSItem) => {
    const existing = cart.find(c => c.item.id === item.id);
    if (existing) {
      setCart(cart.map(c => c.item.id === item.id ? {...c, quantity: c.quantity + 1} : c));
    } else {
      setCart([...cart, {item, quantity: 1}]);
    }
  };

  const removeFromCart = (id: string) => {
    setCart(cart.filter(c => c.item.id !== id));
  };

  const updateQuantity = (id: string, delta: number) => {
    setCart(cart.map(c => {
      if (c.item.id === id) {
        const newQty = Math.max(1, c.quantity + delta);
        return {...c, quantity: newQty};
      }
      return c;
    }));
  };

  const subtotal = cart.reduce((acc, curr) => acc + (curr.item.price * curr.quantity), 0);
  const totalItems = cart.reduce((a, b) => a + b.quantity, 0);
  const filteredItems = MOCK_POS_ITEMS.filter(item => {
    const matchesCat = activeCategory === 'all' || item.category === activeCategory;
    const matchesSearch = item.name.toLowerCase().includes(searchQuery.toLowerCase());
    return matchesCat && matchesSearch;
  });

  const handleCheckout = () => {
    if (cart.length === 0) return;
    onCheckout(subtotal, totalItems);
    setCart([]);
  };

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 flex flex-col lg:flex-row gap-8 h-[calc(100vh-160px)]">
      {/* Products Selection */}
      <div className="flex-1 flex flex-col gap-6 overflow-hidden">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
          <div>
            <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
              <ShoppingCart className="text-amber-500" size={32} />
              Caisse & Snacks
            </h1>
            <p className="text-slate-500 font-medium mt-1 italic">Ventes directes de compléments et boissons 🧖</p>
          </div>
          <div className="flex items-center gap-3">
            <div className="relative">
              <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={20} />
              <input 
                type="text" 
                placeholder="Rechercher produit..." 
                className="pl-12 pr-4 py-3 bg-white border border-slate-100 rounded-2xl outline-none focus:border-indigo-500 font-bold text-sm w-full md:w-64 shadow-sm"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
            </div>
            <button 
              onClick={onAddItem}
              className="p-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 group"
              title="Ajouter un nouveau produit à l'inventaire"
            >
              <PlusCircle size={24} className="group-hover:rotate-90 transition-transform duration-300" />
            </button>
          </div>
        </div>

        <div className="flex gap-2 overflow-x-auto pb-2 shrink-0 no-scrollbar">
          {[
            { id: 'all', label: 'Tout' },
            { id: 'complement', label: 'Compléments' },
            { id: 'snack', label: 'Snacks' },
            { id: 'boisson', label: 'Boissons' },
          ].map(cat => (
            <button 
              key={cat.id}
              onClick={() => setActiveCategory(cat.id as any)}
              className={`px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all whitespace-nowrap border ${
                activeCategory === cat.id ? 'bg-slate-900 text-white border-slate-900 shadow-lg' : 'bg-white text-slate-400 border-slate-100 hover:border-slate-300'
              }`}
            >
              {cat.label}
            </button>
          ))}
        </div>

        <div className="flex-1 overflow-y-auto pr-4 grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
          {filteredItems.map(item => (
            <button 
              key={item.id}
              onClick={() => addToCart(item)}
              className="bg-white p-5 rounded-[28px] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all text-left flex flex-col group active:scale-95"
            >
              <div className={`h-32 w-full rounded-2xl mb-4 flex items-center justify-center text-4xl group-hover:scale-110 transition-transform ${
                item.category === 'complement' ? 'bg-indigo-50 text-indigo-500' :
                item.category === 'snack' ? 'bg-amber-50 text-amber-500' : 'bg-emerald-50 text-emerald-500'
              }`}>
                {item.category === 'complement' ? '💊' : item.category === 'snack' ? '🍫' : '🥤'}
              </div>
              <p className="text-[10px] font-black uppercase text-slate-400 mb-1">{item.category}</p>
              <h3 className="text-sm font-black text-slate-900 flex-1 leading-tight">{item.name}</h3>
              <div className="mt-4 flex items-center justify-between">
                <span className="text-lg font-black text-slate-900">{item.price} DH</span>
                <span className="text-[10px] font-bold text-slate-400">Stock: {item.stock}</span>
              </div>
            </button>
          ))}
          
          <button 
            onClick={onAddItem}
            className="p-5 rounded-[28px] border-2 border-dashed border-slate-200 text-slate-300 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all flex flex-col items-center justify-center text-center gap-3 py-12"
          >
            <Plus size={32} />
            <span className="text-xs font-black uppercase tracking-widest">Nouveau Produit</span>
          </button>
        </div>
      </div>

      {/* Cart Sidebar */}
      <div className="w-full lg:w-96 bg-white rounded-[40px] border border-slate-100 shadow-2xl flex flex-col overflow-hidden animate-in slide-in-from-right-8 duration-500">
        <div className="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
           <h2 className="text-xl font-black text-slate-900 flex items-center gap-2">
             <ShoppingCart size={24} className="text-indigo-600" /> Panier
           </h2>
           <span className="bg-indigo-600 text-white text-[10px] font-black px-2 py-1 rounded-full">{totalItems} ARTICLES</span>
        </div>

        <div className="flex-1 overflow-y-auto p-6 space-y-4">
          {cart.length > 0 ? (
            cart.map(entry => (
              <div key={entry.item.id} className="flex gap-4 group animate-in slide-in-from-right-4">
                <div className={`h-16 w-16 rounded-2xl flex items-center justify-center text-2xl shrink-0 ${
                  entry.item.category === 'complement' ? 'bg-indigo-50' : 'bg-slate-50'
                }`}>
                  {entry.item.category === 'complement' ? '💊' : entry.item.category === 'snack' ? '🍫' : '🥤'}
                </div>
                <div className="flex-1 min-w-0">
                  <div className="flex justify-between items-start">
                    <h4 className="text-sm font-black text-slate-900 truncate pr-2">{entry.item.name}</h4>
                    <button onClick={() => removeFromCart(entry.item.id)} className="text-slate-300 hover:text-rose-500 transition-colors">
                      <X size={16} />
                    </button>
                  </div>
                  <p className="text-xs font-bold text-slate-400">{entry.item.price} DH / un.</p>
                  <div className="mt-2 flex items-center justify-between">
                     <div className="flex items-center bg-slate-100 rounded-lg p-1">
                        <button onClick={() => updateQuantity(entry.item.id, -1)} className="p-1 hover:bg-white rounded transition-colors text-slate-500"><Minus size={12} /></button>
                        <span className="px-3 text-xs font-black">{entry.quantity}</span>
                        <button onClick={() => updateQuantity(entry.item.id, 1)} className="p-1 hover:bg-white rounded transition-colors text-slate-500"><Plus size={12} /></button>
                     </div>
                     <span className="text-sm font-black text-indigo-600">{entry.item.price * entry.quantity} DH</span>
                  </div>
                </div>
              </div>
            ))
          ) : (
            <div className="h-full flex flex-col items-center justify-center text-center space-y-4 text-slate-300 py-20">
              <Package size={64} className="opacity-20" />
              <p className="text-sm font-bold">Votre panier est vide.</p>
            </div>
          )}
        </div>

        <div className="p-8 bg-slate-900 text-white space-y-6">
          <div className="space-y-3">
             <div className="flex justify-between text-slate-400 text-xs font-bold uppercase tracking-widest">
                <span>Sous-total</span>
                <span>{subtotal} DH</span>
             </div>
             <div className="flex justify-between text-indigo-400 text-xs font-bold uppercase tracking-widest">
                <span>Remise</span>
                <span>0 DH</span>
             </div>
             <div className="pt-3 border-t border-white/10 flex justify-between items-baseline">
                <span className="text-sm font-black">TOTAL À PAYER</span>
                <span className="text-4xl font-black text-emerald-400">{subtotal} <span className="text-xl">DH</span></span>
             </div>
          </div>

          <div className="grid grid-cols-2 gap-3">
             <button onClick={handleCheckout} className="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all group">
                <Banknote size={20} className="mb-2 text-emerald-400 group-hover:scale-110 transition-transform" />
                <span className="text-[10px] font-black uppercase">Espèces</span>
             </button>
             <button onClick={handleCheckout} className="flex flex-col items-center justify-center p-4 bg-indigo-600 hover:bg-indigo-700 rounded-2xl transition-all shadow-lg shadow-indigo-500/20 group">
                <CreditCard size={20} className="mb-2 text-white group-hover:scale-110 transition-transform" />
                <span className="text-[10px] font-black uppercase">Carte / NFC</span>
             </button>
          </div>
          
          <div className="flex items-center justify-center gap-2 text-[10px] font-black uppercase text-white/30 tracking-tighter">
             <Zap size={10} /> Commande synchronisée avec le stock
          </div>
        </div>
      </div>
    </div>
  );
};

export default POSView;
