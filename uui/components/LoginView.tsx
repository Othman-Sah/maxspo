
import React, { useState } from 'react';
import { Trophy, Mail, Lock, Eye, EyeOff, Loader2, ArrowRight, ShieldCheck, Waves } from 'lucide-react';

interface LoginViewProps {
  onLogin: () => void;
}

const LoginView: React.FC<LoginViewProps> = ({ onLogin }) => {
  const [email, setEmail] = useState('admin@needsport.ma');
  const [password, setPassword] = useState('password');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    
    // Simulate API delay for a professional feel
    setTimeout(() => {
      setIsLoading(false);
      onLogin();
    }, 1800);
  };

  return (
    <div className="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6 font-['Inter']">
      <div className="w-full max-w-5xl bg-white rounded-[40px] shadow-2xl shadow-slate-200 overflow-hidden flex flex-col md:flex-row min-h-[600px] animate-in fade-in zoom-in-95 duration-700">
        
        {/* Left Side: Hero Section */}
        <div className="md:w-5/12 bg-slate-900 relative p-12 flex flex-col justify-between overflow-hidden">
          {/* Animated background blobs */}
          <div className="absolute -top-20 -left-20 w-64 h-64 bg-indigo-600/20 rounded-full blur-[100px] animate-pulse"></div>
          <div className="absolute -bottom-20 -right-20 w-64 h-64 bg-emerald-600/10 rounded-full blur-[100px] animate-pulse delay-700"></div>
          
          <div className="relative z-10">
            <div className="flex items-center gap-3 mb-12">
              <div className="bg-indigo-600 p-2.5 rounded-2xl text-white shadow-lg shadow-indigo-500/20">
                <Trophy size={28} />
              </div>
              <span className="text-2xl font-black text-white tracking-tight">NEEDSPORT</span>
            </div>
            
            <div className="space-y-6">
              <h1 className="text-4xl lg:text-5xl font-black text-white leading-tight">
                Gérez votre club comme un <span className="text-indigo-400">champion.</span>
              </h1>
              <p className="text-slate-400 text-sm font-medium leading-relaxed max-w-xs">
                La plateforme de gestion tout-en-un pour les clubs de sport d'élite. Suivi des membres, revenus et planning en temps réel.
              </p>
            </div>
          </div>

          <div className="relative z-10">
            <div className="flex items-center gap-3 p-4 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-sm">
              <div className="h-10 w-10 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400">
                <ShieldCheck size={20} />
              </div>
              <div>
                <p className="text-xs font-black text-white uppercase tracking-widest">Accès Sécurisé</p>
                <p className="text-[10px] text-slate-500 font-bold">Chiffrement SSL 256-bit actif</p>
              </div>
            </div>
            <p className="text-[10px] text-slate-600 mt-6 font-bold uppercase tracking-widest">© 2024 NEEDSPOORT PRO VERSION 2.4</p>
          </div>
        </div>

        {/* Right Side: Login Form */}
        <div className="md:w-7/12 p-8 lg:p-16 flex flex-col justify-center bg-white">
          <div className="max-w-md mx-auto w-full space-y-10">
            <div>
              <h2 className="text-3xl font-black text-slate-900 tracking-tight">Content de vous revoir !</h2>
              <p className="text-slate-500 font-medium mt-2">Connectez-vous pour accéder au tableau de bord.</p>
            </div>

            <form onSubmit={handleSubmit} className="space-y-6">
              <div className="space-y-2">
                <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Adresse Email</label>
                <div className="relative group">
                  <Mail className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors" size={20} />
                  <input 
                    type="email" 
                    required
                    className="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                    placeholder="admin@needsport.ma"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                  />
                </div>
              </div>

              <div className="space-y-2">
                <div className="flex justify-between items-center ml-1">
                  <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest">Mot de passe</label>
                  <button type="button" className="text-[10px] font-black uppercase text-indigo-600 hover:underline">Oublié ?</button>
                </div>
                <div className="relative group">
                  <Lock className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors" size={20} />
                  <input 
                    type={showPassword ? 'text' : 'password'} 
                    required
                    className="w-full pl-12 pr-12 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                    placeholder="••••••••"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                  />
                  <button 
                    type="button"
                    onClick={() => setShowPassword(!showPassword)}
                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600 transition-colors"
                  >
                    {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
                  </button>
                </div>
              </div>

              <div className="flex items-center gap-2 ml-1">
                <input type="checkbox" id="remember" className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                <label htmlFor="remember" className="text-xs font-bold text-slate-500 cursor-pointer">Se souvenir de moi</label>
              </div>

              <button 
                type="submit"
                disabled={isLoading}
                className="w-full py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-[0.98] flex items-center justify-center gap-3 group"
              >
                {isLoading ? (
                  <>
                    <Loader2 size={20} className="animate-spin" />
                    <span className="uppercase tracking-widest text-xs">Vérification...</span>
                  </>
                ) : (
                  <>
                    <span className="uppercase tracking-widest text-xs">Se connecter</span>
                    <ArrowRight size={20} className="group-hover:translate-x-1 transition-transform" />
                  </>
                )}
              </button>
            </form>

            <div className="pt-8 border-t border-slate-50 text-center">
              <p className="text-xs font-bold text-slate-400">
                Pas encore de compte ? <button className="text-indigo-600 hover:underline">Contactez le support</button>
              </p>
            </div>
            
            <div className="flex items-center justify-center gap-6 pt-4 opacity-20 grayscale">
              <div className="flex items-center gap-1 font-black text-slate-900 text-[10px]"><Waves size={12} /> SAUNA OPS</div>
              <div className="flex items-center gap-1 font-black text-slate-900 text-[10px]"><Trophy size={12} /> ELITE CLUB</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LoginView;
