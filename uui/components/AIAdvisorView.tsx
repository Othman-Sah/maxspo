
import React, { useState, useRef, useEffect } from 'react';
import { Send, Bot, Sparkles, BrainCircuit, RefreshCcw, LayoutDashboard, TrendingUp, Users } from 'lucide-react';
import { GoogleGenAI } from "@google/genai";

const AIAdvisorView: React.FC = () => {
  const [query, setQuery] = useState('');
  const [isThinking, setIsThinking] = useState(false);
  const [response, setResponse] = useState<string | null>(null);
  const scrollRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (scrollRef.current) {
      scrollRef.current.scrollTop = scrollRef.current.scrollHeight;
    }
  }, [response, isThinking]);

  const handleConsult = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!query.trim() || isThinking) return;

    setIsThinking(true);
    setResponse(null);

    try {
      const ai = new GoogleGenAI({ apiKey: process.env.API_KEY });
      const res = await ai.models.generateContent({
        model: "gemini-3-pro-preview",
        contents: `Tu es un conseiller stratégique expert en gestion de clubs de sport (NEEDSPORT). 
        Utilise tes capacités de réflexion approfondie pour répondre à cette requête complexe concernant la gestion du club, 
        l'optimisation des revenus ou la rétention des membres.
        
        Requête de l'administrateur : ${query}`,
        config: {
          thinkingConfig: { thinkingBudget: 32768 }
        },
      });

      setResponse(res.text || "Désolé, je n'ai pas pu générer de réponse.");
    } catch (error) {
      console.error(error);
      setResponse("Une erreur est survenue lors de la consultation de l'IA. Veuillez vérifier votre clé API.");
    } finally {
      setIsThinking(false);
    }
  };

  const suggestions = [
    "Comment optimiser mes revenus pour le CrossFit ?",
    "Stratégie de rétention pour les membres expirant bientôt",
    "Analyse de la rentabilité Fitness vs Yoga",
    "Idées de promotions pour booster les inscriptions d'été"
  ];

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 max-w-4xl mx-auto flex flex-col h-[calc(100vh-160px)]">
      <div className="flex items-center justify-between mb-6 shrink-0">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
            <BrainCircuit className="text-indigo-600" size={32} />
            Conseiller IA Stratégique
          </h1>
          <p className="text-slate-500 font-medium mt-1 italic">Mode "Deep Thinking" activé pour des analyses complexes.</p>
        </div>
        <div className="flex items-center gap-2 px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
          <Sparkles size={14} className="animate-pulse" />
          <span className="text-[10px] font-black uppercase tracking-widest">Gemini 3 Pro</span>
        </div>
      </div>

      <div className="flex-1 bg-white rounded-[32px] border border-slate-100 shadow-xl overflow-hidden flex flex-col">
        {/* Chat Area */}
        <div ref={scrollRef} className="flex-1 overflow-y-auto p-8 space-y-6">
          {!response && !isThinking && (
            <div className="h-full flex flex-col items-center justify-center text-center space-y-8 py-12">
              <div className="h-24 w-24 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 relative">
                <Bot size={48} />
                <div className="absolute -top-2 -right-2 bg-white p-2 rounded-xl shadow-md border border-slate-50">
                  <Sparkles size={16} className="text-amber-500" />
                </div>
              </div>
              <div className="max-w-md space-y-2">
                <h3 className="text-xl font-black text-slate-900">Posez une question stratégique</h3>
                <p className="text-slate-500 font-medium text-sm">
                  L'IA analysera vos données de club pour vous fournir des conseils sur mesure pour NEEDSPORT.
                </p>
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full max-w-2xl">
                {suggestions.map((s) => (
                  <button 
                    key={s} 
                    onClick={() => setQuery(s)}
                    className="p-4 bg-slate-50 hover:bg-indigo-50 border border-slate-100 hover:border-indigo-200 rounded-2xl text-left text-xs font-bold text-slate-600 transition-all"
                  >
                    {s}
                  </button>
                ))}
              </div>
            </div>
          )}

          {isThinking && (
            <div className="flex gap-4 animate-in slide-in-from-left-4">
              <div className="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shrink-0">
                <Bot size={20} />
              </div>
              <div className="space-y-4 max-w-[80%]">
                <div className="flex items-center gap-2">
                   <div className="flex gap-1">
                      <div className="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce"></div>
                      <div className="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce delay-75"></div>
                      <div className="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce delay-150"></div>
                   </div>
                   <span className="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Analyse en cours...</span>
                </div>
                <div className="p-5 bg-slate-50 rounded-3xl border border-slate-100 space-y-2">
                   <div className="h-2 w-full bg-slate-200 rounded animate-pulse"></div>
                   <div className="h-2 w-3/4 bg-slate-200 rounded animate-pulse"></div>
                   <div className="h-2 w-1/2 bg-slate-200 rounded animate-pulse"></div>
                </div>
              </div>
            </div>
          )}

          {response && (
            <div className="flex gap-4 animate-in slide-in-from-left-4">
              <div className="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shrink-0">
                <Bot size={20} />
              </div>
              <div className="p-6 bg-slate-50 rounded-3xl border border-slate-100 text-slate-700 text-sm font-medium leading-relaxed max-w-[90%] whitespace-pre-wrap">
                {response}
              </div>
            </div>
          )}
        </div>

        {/* Input Area */}
        <div className="p-6 border-t border-slate-50 bg-white">
          <form onSubmit={handleConsult} className="relative">
            <textarea 
              rows={2}
              placeholder="Ex: Comment puis-je augmenter le taux de renouvellement de 15% ?"
              className="w-full pl-6 pr-16 py-4 bg-slate-100 border-2 border-transparent focus:bg-white focus:border-indigo-500 rounded-[24px] outline-none transition-all font-bold text-sm resize-none"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              onKeyDown={(e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                  e.preventDefault();
                  handleConsult(e);
                }
              }}
            />
            <button 
              type="submit"
              disabled={!query.trim() || isThinking}
              className={`absolute right-3 top-1/2 -translate-y-1/2 h-12 w-12 rounded-2xl flex items-center justify-center transition-all ${
                query.trim() && !isThinking ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 hover:scale-110 active:scale-95' : 'bg-slate-200 text-slate-400'
              }`}
            >
              <Send size={20} />
            </button>
          </form>
          <p className="text-center mt-3 text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center justify-center gap-2">
            <Info size={10} />
            Basé sur les données réelles de NEEDSPORT
          </p>
        </div>
      </div>
    </div>
  );
};

const Info = ({ size }: { size: number }) => <span className="opacity-50">ⓘ</span>;

export default AIAdvisorView;
