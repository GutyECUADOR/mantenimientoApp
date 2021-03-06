USE [KAO_wssp]
GO
/****** Object:  Table [dbo].[mantenimientosEQ]    Script Date: 11/07/2018 15:09:18 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[mantenimientosEQ](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[codMantenimiento] [nchar](10) NOT NULL,
	[tipo] [nchar](10) NULL,
	[codFactura] [char](17) NOT NULL,
	[codEquipo] [char](20) NOT NULL,
	[codEmpresa] [nchar](10) NOT NULL,
	[fechaInicio] [datetime] NOT NULL,
	[fechaFin] [datetime] NOT NULL,
	[cantidad] [nchar](10) NULL,
	[comentario] [nchar](200) NULL,
	[responsable] [nchar](13) NULL,
	[estado] [nchar](2) NULL,
 CONSTRAINT [PK_mantenimientosEQ] PRIMARY KEY CLUSTERED 
(
	[codMantenimiento] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[tiposDOC]    Script Date: 11/07/2018 15:09:18 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tiposDOC](
	[codigo] [nchar](10) NULL,
	[Descripcion] [nchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  StoredProcedure [dbo].[sp_genera_codMantenimiento]    Script Date: 11/07/2018 15:09:21 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_genera_codMantenimiento]
@tipo_doc nchar(3)
AS
BEGIN
    DECLARE @contador AS INT
		SET @contador = (SELECT COUNT(*)+1 FROM dbo.mantenimientosEQ)
		
        IF (@contador>=1) BEGIN
			IF (@contador<10) BEGIN
				SELECT @tipo_doc +'0000'+ CONVERT (Varchar ,@contador)
			END
			ELSE IF (@contador<100) BEGIN    
				SELECT @tipo_doc +'000'+ CONVERT (Varchar ,@contador)
			END
			ELSE IF (@contador<1000) BEGIN
				SELECT @tipo_doc +'00'+ CONVERT (Varchar ,@contador)
			END
			ELSE IF (@contador<1000) BEGIN
				SELECT @tipo_doc +'0'+ CONVERT (Varchar ,@contador)
			END
			
		END	
END
GO
/****** Object:  Default [DF_mantenimientosEQ_estado]    Script Date: 11/07/2018 15:09:18 ******/
ALTER TABLE [dbo].[mantenimientosEQ] ADD  CONSTRAINT [DF_mantenimientosEQ_estado]  DEFAULT ((0)) FOR [estado]
GO
